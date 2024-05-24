<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\UpdateQuotedArticle;

use Proyecto\Application\UseCase\UpdateQuotedArticle\UpdateQuotedArticleRequest;
use Proyecto\Application\UseCase\UpdateQuotedArticle\UpdateQuotedArticleUseCase;
use Proyecto\Domain\Model\Quote\Exception\QuotedArticleNotFoundException;
use Proyecto\Domain\Model\Quote\QuotedArticle;
use Proyecto\Domain\Model\Quote\QuotedArticleRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UpdateQuotedArticleTest extends TestCase
{
    private UpdateQuotedArticleUseCase $useCase;
    private QuotedArticleRepository $quotedArticleRepository;

    public function test_given_valid_quoted_article_when_update_then_ok(): void
    {
        $id = '20e89f7a-958a-4777-993e-84d09ba2792d';

        $quotedArticle = QuotedArticle::createFromAllParams(
            Uuid::fromRfc4122($id),
            'name',
            1000,
            2000,
        );

        $this->quotedArticleRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn($quotedArticle);

        $this->quotedArticleRepository->expects($this->once())
            ->method('flush');

        $request = UpdateQuotedArticleRequest::fromPayload(
            Uuid::v4(),
            [
                UpdateQuotedArticleRequest::PARAM_ID => $id,
                UpdateQuotedArticleRequest::PARAM_NAME => 'updated name',
                UpdateQuotedArticleRequest::PARAM_VOLUME => 1001.00,
                UpdateQuotedArticleRequest::PARAM_WEIGHT => 2001,
            ],
        );

        $this->useCase->__invoke($request);
        $this->assertEquals('updated name', $quotedArticle->name());
        $this->assertEquals(1001.00, $quotedArticle->volumeInM3());
        $this->assertEquals(2001, $quotedArticle->weightInGrams());
    }

    public function test_given_quoted_article_not_exists_when_update_then_fail(): void
    {
        $id = '20e89f7a-958a-4777-993e-84d09ba2792d';
        $request = UpdateQuotedArticleRequest::fromPayload
        (
            Uuid::v4(),
            [
                UpdateQuotedArticleRequest::PARAM_ID => $id,
                UpdateQuotedArticleRequest::PARAM_NAME => 'name',
                UpdateQuotedArticleRequest::PARAM_VOLUME => 101.00,
                UpdateQuotedArticleRequest::PARAM_WEIGHT => 1053,
            ]
        );

        $this->quotedArticleRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn(null);

        $this->quotedArticleRepository->expects($this->never())
            ->method('flush');

        $this->expectException(QuotedArticleNotFoundException::class);
        $this->useCase->__invoke($request);
    }

    public function setUp(): void
    {
        $this->quotedArticleRepository = $this->createMock(QuotedArticleRepository::class);
        $this->useCase = new UpdateQuotedArticleUseCase(
            $this->quotedArticleRepository,
        );
    }
}
