<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\DeleteQuotedArticle;

use Proyecto\Domain\Model\Quote\QuotedArticleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteQuotedArticleUseCase
{
    public function __construct(private QuotedArticleRepository $quotedArticleRepository)
    {
        // Left intentionally blank
    }

    public function __invoke(DeleteQuotedArticleRequest $request): DeleteQuotedArticleResponse
    {
        $quotedArticle = $this->quotedArticleRepository->findOneBy(['id' => $request->id()]);

        if (null !== $quotedArticle) {
            $this->quotedArticleRepository->remove($quotedArticle);
            $this->quotedArticleRepository->flush();
        }

        return new DeleteQuotedArticleResponse();
    }
}
