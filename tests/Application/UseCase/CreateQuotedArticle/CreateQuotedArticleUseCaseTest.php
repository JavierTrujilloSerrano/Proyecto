<?php
declare(strict_types=1);

namespace Application\UseCase\CreateQuotedArticle;

use Proyecto\Application\UseCase\CreateQuotedArticle\CreateQuotedArticleRequest;
use Proyecto\Application\UseCase\CreateQuotedArticle\CreateQuotedArticleResponse;
use Proyecto\Application\UseCase\CreateQuotedArticle\CreateQuotedArticleUseCase;
use Proyecto\Domain\Model\Quote\QuotedArticle;
use Proyecto\Domain\Model\Quote\QuotedArticleRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CreateQuotedArticleUseCaseTest extends TestCase
{
    private CreateQuotedArticleUseCase $useCase;
    private CreateQuotedArticleRequest $createQuotedArticleRequest;
    private QuotedArticleRepository $quotedArticleRepository;

    public function test_given_valid_quoted_article_when_create_then_ok(): void
    {
        $volume = 180.00;
        $weight = 203;
        $request = CreateQuotedArticleRequest::fromPayload(
            Uuid::v4(),
            [
                CreateQuotedArticleRequest::PARAM_ID => '20e89f7a-558a-4779-993e-84d08ba2792d',
                CreateQuotedArticleRequest::PARAM_NAME => 'name',
                CreateQuotedArticleRequest::PARAM_VOLUME => $volume,
                CreateQuotedArticleRequest::PARAM_WEIGHT => $weight,
            ],
        );
        $quotedArticle = QuotedArticle::createFromAllParams(
            Uuid::fromString('20e89f7a-558a-4779-993e-84d08ba2792d'),
            'name',
            $volume,
            $weight,
        );

        $this->quotedArticleRepository->expects($this->once())
            ->method('persistAndFlush')
            ->with($quotedArticle);

        $quotedArticle = $this->useCase->__invoke($request);
        $this->assertInstanceOf(CreateQuotedArticleResponse::class, $quotedArticle);

    }

    public function setUp(): void
    {
        $this->quotedArticleRepository = $this->createMock(QuotedArticleRepository::class);
        $this->useCase = new CreateQuotedArticleUseCase(
            $this->quotedArticleRepository,
        );
    }

}
