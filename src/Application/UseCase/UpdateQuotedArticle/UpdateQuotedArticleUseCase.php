<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\UpdateQuotedArticle;

use Proyecto\Domain\Model\Quote\Exception\QuotedArticleNotFoundException;
use Proyecto\Domain\Model\Quote\QuotedArticle;
use Proyecto\Domain\Model\Quote\QuotedArticleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
readonly class UpdateQuotedArticleUseCase
{
    public function __construct(private QuotedArticleRepository $quotedArticleRepository)
    {
        // Left intentionally blank
    }

    /**
     * @throws QuotedArticleNotFoundException
     */
    public function __invoke(UpdateQuotedArticleRequest $request): UpdateQuotedArticleResponse
    {
        $quotedArticle = $this->findQuotedArticleOrFail($request->id());

        $quotedArticle
            ->setName($request->name())
            ->setVolumeInM3($request->volume())
            ->setWeightInGrams($request->weight());

        $this->quotedArticleRepository->flush();

        return UpdateQuotedArticleResponse::build();
    }

    /** @throws QuotedArticleNotFoundException */
    private function findQuotedArticleOrFail(Uuid $id): QuotedArticle
    {
        $quotedArticle = $this->quotedArticleRepository->findOneBy(['id' => $id]);

        if (null === $quotedArticle) {
            throw QuotedArticleNotFoundException::fromId($id);
        }

        return $quotedArticle;
    }
}
