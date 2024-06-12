<?php
declare(strict_types=1);

namespace Proyecto\Infrastructure\Entrypoint\MVC\Admin;

use Proyecto\Application\UseCase\CreateQuotedArticle\CreateQuotedArticleRequest;
use Proyecto\Application\UseCase\DeleteQuotedArticle\DeleteQuotedArticleRequest;
use Proyecto\Application\UseCase\UpdateQuotedArticle\UpdateQuotedArticleRequest;
use Proyecto\Domain\Model\Cost\Cost;
use Proyecto\Domain\Model\Quote\Exception\QuotedArticleNotFoundException;
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
    /*
     * tratamos los datos que nos llegan
     * [Un trait es un conjunto de métodos que puedes incluir en una o más clases. Los traits permiten compartir
     * funcionalidades entre clases sin la necesidad de herencia múltiple] **
    */
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly QuotedArticleRepository $quotedArticleRepository,
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
            'ES-AN' => ['zone' => 'zona 3', 'value' => 30],
            'ES-AR' => ['zone' => 'zona 1', 'value' => 10],
            'ES-AS' => ['zone' => 'zona 1', 'value' => 10],
            'ES-IB' => ['zone' => 'zona 4', 'value' => 40],
            'ES-CN' => ['zone' => 'zona 5', 'value' => 50],
            'ES-CB' => ['zone' => 'zona 1', 'value' => 10],
            'ES-CM' => ['zone' => 'zona 2', 'value' => 20],
            'ES-CL' => ['zone' => 'zona 2', 'value' => 20],
            'ES-CT' => ['zone' => 'zona 1', 'value' => 10],
            'ES-EX' => ['zone' => 'zona 2', 'value' => 20],
            'ES-GA' => ['zone' => 'zona 1', 'value' => 10],
            'ES-MD' => ['zone' => 'zona 2', 'value' => 20],
            'ES-MC' => ['zone' => 'zona 3', 'value' => 30],
            'ES-NC' => ['zone' => 'zona 1', 'value' => 10],
            'ES-PV' => ['zone' => 'zona 1', 'value' => 10],
            'ES-RI' => ['zone' => 'zona 1', 'value' => 10],
            'ES-VC' => ['zone' => 'zona 3', 'value' => 30],
            'ES-CE' => ['zone' => 'zona 4', 'value' => 40],
            'ES-ML' => ['zone' => 'zona 4', 'value' => 40],
        ];

        //renderizamos la vista y nos llevamos lo valores de los objetos y de la base de datos
        return $this->render(
            'Admin/cost_simulator.html.twig',
            [
                'costs' => $cost,
                'quotedArticles' => $quotedArticleRepository->findAllSortedByName(),
                'shipmentDeliveryZones' => $shipmentDelivery,
            ],
        );
    }

    //controlador de la vista de QuotedArticle donde aparecera la vista listando los QuotedArticle que tenemos en la base de datos
    #[Cache(public: false)]
    #[Route('/cost-simulator/quoted-article', name: 'admin_quoted_article')]
    public function listQuotedArticle(QuotedArticleRepository $quotedArticleRepository): Response
    {
        $quotedArticles = $quotedArticleRepository->findAllSortedByName();

        return $this->render('Admin/cost_simulator_quoted_article_list.html.twig', [
            'quotedArticles' => $quotedArticles,
        ]);
    }

    // controlador de la vista de creación de QuotedArticle que luego se suben a la base de datos
    #[Cache(public: false)]
    #[Route('/cost-simulator/quoted-article/new', name: 'admin_create_quoted_article')]
    public function createQuotedArticle(Request $request): Response
    {
        //creamos el formulario para crear un nuevo quotedArticle
        $form = $this->createForm(CreateOrUpdateQuotedArticleForm::class);
        $form->handleRequest($request);

        //sí cuando se envía el formulario es válido y tiene formato de array (assert(\is_array)), actualizar el QuotedArticle
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            \assert(\is_array($data));

            $createQuotedArticleRequest = CreateQuotedArticleRequest::fromPayload(
                Uuid::v4(),
                [
                    CreateQuotedArticleRequest::PARAM_ID => Uuid::v4()->toRfc4122(),
                    CreateQuotedArticleRequest::PARAM_NAME => $data['name'] ?? null,
                    CreateQuotedArticleRequest::PARAM_VOLUME => $data['volumeInCm3'],
                    CreateQuotedArticleRequest::PARAM_WEIGHT => $data['weightInGrams'],
                ],
            );
            $this->handle($createQuotedArticleRequest);

            //mensaje de confirmación
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

    // controlador de la vista de actualización de QuotedArticle donde se selecciona el QuotedArticle y además mostramos
    // sus parámetros y que podemos modificar para que luego se suban a la base de datos ya modificados
    #[Cache(public: false)]
    #[Route('/cost-simulator/quoted-article/{quotedArticleId}/update', name: 'admin_quoted_article_update')]
    public function updateQuotedArticle(Uuid $quotedArticleId, Request $request): Response
    {
        //para que nos muestre la excepción si no encuentra el artículo por el id de update
        $quotedArticle = $this->quotedArticleRepository->findOneBy(['id' => $quotedArticleId]);

        if (null === $quotedArticle) {
            throw QuotedArticleNotFoundException::fromId($quotedArticleId);
        }

        //rellenamos el formulario con los datos del QuotedArticle que vamos a modificar
        $form = $this->createForm(
            CreateOrUpdateQuotedArticleForm::class,
            [
                'name' => $quotedArticle->name(),
                'volumeInCm3' => $quotedArticle->volumeInCm3(),
                'weightInGrams' => $quotedArticle->weightInGrams(),
            ],
        );

        $form->handleRequest($request);

        //sí cuando se envía el formulario es válido y tiene formato de array (assert(\is_array)), actualizar el QuotedArticle
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            \assert(\is_array($data));

            $updateQuotedArticleRequest = UpdateQuotedArticleRequest::fromPayload(
                Uuid::v4(),
                [
                    UpdateQuotedArticleRequest::PARAM_ID => $quotedArticle->id()->toRfc4122(),
                    UpdateQuotedArticleRequest::PARAM_NAME => $data['name'] ?? null,
                    UpdateQuotedArticleRequest::PARAM_VOLUME => $data['volumeInCm3'],
                    UpdateQuotedArticleRequest::PARAM_WEIGHT => $data['weightInGrams'],
                ],
            );

            $this->handle($updateQuotedArticleRequest);
            $this->addFlash('success', new TranslatableMessage(
                'notification.quotedArticle.updated',
                ['%%name%%' => $data['name']],
                'notifications',
            ));

            //volvemos a la vista del listado
            return $this->redirectToRoute('admin_quoted_article');
        }

        //devolvemos la vista con el QuotedArticle
        return $this->render(
            'Admin/cost_simulator_quoted_article.html.twig',
            [
                'form' => $form->createView(),
                'quotedArticle' => $quotedArticle,
            ],
        );
    }

    #[Cache(public: false)]
    #[Route('/cost-simulator/quoted-article/{quotedArticleId}/delete', name: 'admin_quoted_article_delete')]
    public function deleteQuotedArticle(Uuid $quotedArticleId, Request $request): Response
    {
        $deleteQuotedArticle = DeleteQuotedArticleRequest::fromPayload(
            Uuid::v4(),
            [DeleteQuotedArticleRequest::PARAM_ID => $quotedArticleId->toRfc4122()],
        );

        $response = $this->handle($deleteQuotedArticle);

        $this->addFlash('success', new TranslatableMessage(
            'notification.quotedArticle.deleted',
            ['%%name%%' => $response->name()],
            'notifications',
        ));

        return $this->redirectToRoute('admin_quoted_article');
    }

}
