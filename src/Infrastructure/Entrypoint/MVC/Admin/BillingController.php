<?php
declare(strict_types=1);

namespace Proyecto\Infrastructure\Entrypoint\MVC\Admin;

use Proyecto\Application\UseCase\CreateQuotedArticle\CreateQuotedArticleRequest;
use Proyecto\Application\UseCase\DeleteQuotedArticle\DeleteQuotedArticleRequest;
use Proyecto\Application\UseCase\UpdateQuotedArticle\UpdateQuotedArticleRequest;
use Proyecto\Domain\Model\Cost\Cost;
use Proyecto\Domain\Model\Quote\QuotedArticle;
use Proyecto\Domain\Model\Quote\QuotedArticleRepository;
use Proyecto\Infrastructure\Form\Type\QuotedArticle\CreateOrUpdateQuotedArticleForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Uid\Uuid;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class BillingController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,

    )
    {
        $this->messageBus = $messageBus;
    }

    #[Cache(public: false)]
    #[Route('/cost-simulator', name: 'admin_cost_simulator')]
    public function costSimulator(
        QuotedArticleRepository $quotedArticleRepository,
    ): Response
    {
        $cost = Cost::buildFromScratch();
        $shipmentDelivery = [
            'ES-AN' => ['zone' => 'zona 3', 'value' => 3000],
            'ES-AR' => ['zone' => 'zona 1', 'value' => 1000],
            'ES-AS' => ['zone' => 'zona 1', 'value' => 1000],
            'ES-IB' => ['zone' => 'zona 4', 'value' => 4000],
            'ES-CN' => ['zone' => 'zona 5', 'value' => 5000],
            'ES-CB' => ['zone' => 'zona 1', 'value' => 1000],
            'ES-CM' => ['zone' => 'zona 2', 'value' => 2000],
            'ES-CL' => ['zone' => 'zona 2', 'value' => 2000],
            'ES-CT' => ['zone' => 'zona 1', 'value' => 1000],
            'ES-EX' => ['zone' => 'zona 2', 'value' => 2000],
            'ES-GA' => ['zone' => 'zona 1', 'value' => 1000],
            'ES-MD' => ['zone' => 'zona 2', 'value' => 2000],
            'ES-MC' => ['zone' => 'zona 3', 'value' => 3000],
            'ES-NC' => ['zone' => 'zona 1', 'value' => 1000],
            'ES-PV' => ['zone' => 'zona 1', 'value' => 1000],
            'ES-RI' => ['zone' => 'zona 1', 'value' => 1000],
            'ES-VC' => ['zone' => 'zona 3', 'value' => 3000],
            'ES-CE' => ['zone' => 'zona 4', 'value' => 4000],
            'ES-ML' => ['zone' => 'zona 4', 'value' => 4000],
        ];

        return $this->render(
            'Admin/cost_simulator.html.twig',
            [
                'costs' => $cost,
                'quotedArticles' => $quotedArticleRepository->findAllSortedByName(),
                'shipmentDeliveryZones' => $shipmentDelivery
            ],
        );
    }

    #[Cache(public: false)]
    #[Route('/cost-simulator/quoted-article', name: 'admin_quoted_article')]
    public function listQuotedArticle(QuotedArticleRepository $quotedArticleRepository): Response
    {
        $quotedArticles = $quotedArticleRepository->findAllSortedByName();

        return $this->render('Admin/cost_simulator_quoted_article_list.html.twig', [
            'quotedArticles' => $quotedArticles,
        ]);
    }

    #[Cache(public: false)]
    #[Route('/cost-simulator/quoted-article/new', name: 'admin_create_quoted_article')]
    public function createQuotedArticle(Request $request): Response
    {
        $form = $this->createForm(CreateOrUpdateQuotedArticleForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            \assert(\is_array($data));

            $createQuotedArticleRequest = CreateQuotedArticleRequest::fromPayload(
                Uuid::v4(),
                [
                    CreateQuotedArticleRequest::PARAM_ID => Uuid::v4()->toRfc4122(),
                    CreateQuotedArticleRequest::PARAM_NAME => $data['name'] ?? null,
                    CreateQuotedArticleRequest::PARAM_VOLUME => $data['volumeInM3'],
                    CreateQuotedArticleRequest::PARAM_WEIGHT => $data['weightInGrams'],
                ],
            );
            $this->handle($createQuotedArticleRequest);

            $this->addFlash('success', new TranslatableMessage(
                'notification.quotedArticle.created',
                ['%%name%%' => $data['name']],
                'notifications',
            ));

            return $this->redirectToRoute('admin_quoted_article');
        }

        return $this->render(
            'Admin/cost_simulator_quoted_article.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    #[Cache(public: false)]
    #[Route('/cost-simulator/quoted-article/{quotedArticle}/update', name: 'admin_quoted_article_update')]
    public function updateQuotedArticle(QuotedArticle $quotedArticle, Request $request): Response
    {
        $form = $this->createForm(
            CreateOrUpdateQuotedArticleForm::class,
            [
                'name' => $quotedArticle->name(),
                'volumeInM3' => $quotedArticle->volumeInM3(),
                'weightInGrams' => $quotedArticle->weightInGrams(),
            ],
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            \assert(\is_array($data));

            $updateQuotedArticleRequest = UpdateQuotedArticleRequest::fromPayload(
                Uuid::v4(),
                [
                    UpdateQuotedArticleRequest::PARAM_ID => $quotedArticle->id()->toRfc4122(),
                    UpdateQuotedArticleRequest::PARAM_NAME => $data['name'] ?? null,
                    UpdateQuotedArticleRequest::PARAM_VOLUME => $data['volumeInM3'],
                    UpdateQuotedArticleRequest::PARAM_WEIGHT => $data['weightInGrams'],
                ],
            );

            $this->handle($updateQuotedArticleRequest);
            $this->addFlash('success', new TranslatableMessage(
                'notification.quotedArticle.updated',
                ['%%name%%' => $data['name']],
                'notifications',
            ));

            return $this->redirectToRoute('admin_quoted_article');
        }

        return $this->render(
            'Admin/cost_simulator_quoted_article.html.twig',
            [
                'form' => $form->createView(),
                'quotedArticle' => $quotedArticle,
            ],
        );
    }

    #[Cache(public: false)]
    #[Route('/cost-simulator/quoted-article/{quotedArticle}/delete', name: 'admin_quoted_article_delete')]
    public function deleteQuotedArticle(QuotedArticle $quotedArticle, Request $request): Response
    {
        $deleteQuotedArticle = DeleteQuotedArticleRequest::fromPayload(
            Uuid::v4(),
            [DeleteQuotedArticleRequest::PARAM_ID => $quotedArticle->id()->toRfc4122()],
        );

        $this->handle($deleteQuotedArticle);

        $this->addFlash('success', new TranslatableMessage(
            'notification.quotedArticle.deleted',
            ['%%name%%' => $quotedArticle->name()],
            'notifications',
        ));

        return $this->redirectToRoute('admin_quoted_article');
    }

}
