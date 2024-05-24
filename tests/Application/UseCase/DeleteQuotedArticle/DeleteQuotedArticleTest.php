<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\DeleteQuotedArticle;

use Proyecto\Application\UseCase\DeleteQuotedArticle\DeleteQuotedArticleRequest;
use Proyecto\Application\UseCase\DeleteQuotedArticle\DeleteQuotedArticleUseCase;
use Proyecto\Domain\Model\Quote\QuotedArticle;
use Proyecto\Domain\Model\Quote\QuotedArticleRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class DeleteQuotedArticleTest extends TestCase
{
    private DeleteQuotedArticleUseCase $useCase;
    private QuotedArticleRepository $quotedArticleRepository;

    public function test_given_valid_quoted_article_when_delete_then_ok(): void
    {
        $id = '20e89f7a-958a-4777-993e-84d09ba2792d';
        $request = DeleteQuotedArticleRequest::fromPayload
        (
            Uuid::v4(),
            [
                DeleteQuotedArticleRequest::PARAM_ID => $id,
            ]
        );

        $quotedArticle = QuotedArticle::createFromAllParams
        (
            Uuid::fromString($id),
            'name',
            100,
            100,
        );

        $this->quotedArticleRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn($quotedArticle);

        $this->quotedArticleRepository->expects($this->once())
            ->method('remove');

        $this->quotedArticleRepository->expects($this->once())
            ->method('flush');

        $this->useCase->__invoke($request);
    }

    public function test_given_invalid_quoted_article_when_delete_then_ok(): void
    {
        $id = '20e89f7a-958a-4777-993e-84d09ba2792d';
        $request = DeleteQuotedArticleRequest::fromPayload
        (
            Uuid::v4(),
            [
                DeleteQuotedArticleRequest::PARAM_ID => $id,
            ]
        );
        $this->quotedArticleRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn(null);

        $this->quotedArticleRepository->expects($this->never())
            ->method('remove');

        $this->quotedArticleRepository->expects($this->never())
            ->method('flush');

        $this->useCase->__invoke($request);
    }

    public function setUp(): void
    {
        $this->quotedArticleRepository = $this->createMock(QuotedArticleRepository::class);
        $this->useCase = new DeleteQuotedArticleUseCase(
            $this->quotedArticleRepository,
        );
    }
}
