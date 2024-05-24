<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\CreateQuotedArticle;

use Proyecto\Domain\Model\Quote\QuotedArticle;
use Proyecto\Domain\Model\Quote\QuotedArticleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateQuotedArticleUseCase
{
    public function __construct(private QuotedArticleRepository $quotedArticleRepository)
    {
    }

    public function __invoke(CreateQuotedArticleRequest $request): CreateQuotedArticleResponse
    {
        $quotedArticle = QuotedArticle::createFromAllParams(
            $request->id(),
            $request->name(),
            $request->volume(),
            $request->weight(),
        );

        $this->quotedArticleRepository->persistAndFlush($quotedArticle);

        return CreateQuotedArticleResponse::build();
    }
}
