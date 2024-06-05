<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\DeleteQuotedArticle;

use Assert\Assert;
use Proyecto\Application\UseCase\AbstractRequest;
use Symfony\Component\Uid\Uuid;

class DeleteQuotedArticleRequest extends AbstractRequest
{
    public const PARAM_ID = 'id';

    private Uuid $id;

    public static function messageName(): string
    {
        return 'Proyecto.1.request.quoted_article.deleted';
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    protected function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()->tryAll()
            ->that($payload, 'payload')->isArray()
            ->keyExists(self::PARAM_ID)
            ->verifyNow();

        Assert::lazy()->tryAll()
            ->that($payload[self::PARAM_ID], self::PARAM_ID)->notNull()->uuid()
            ->verifyNow();

        $this->id = Uuid::fromRfc4122($payload[self::PARAM_ID]);
    }
}
