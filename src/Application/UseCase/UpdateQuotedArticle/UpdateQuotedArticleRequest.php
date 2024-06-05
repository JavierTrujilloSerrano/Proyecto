<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\UpdateQuotedArticle;

use Assert\Assert;
use Proyecto\Application\UseCase\AbstractRequest;
use Symfony\Component\Uid\Uuid;

class UpdateQuotedArticleRequest extends AbstractRequest
{
    public const string PARAM_ID = 'id';
    public const string PARAM_NAME = 'name';
    public const string PARAM_VOLUME = 'volume';
    public const string PARAM_WEIGHT = 'weight';

    private Uuid $id;
    private string $name;
    private float $volume;
    private int $weight;

    public static function messageName(): string
    {
        return 'Proyecto.1.request.quoted_article.updated';
    }

    public function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()->tryAll()
            ->that($payload, 'payload')->isArray()
            ->keyExists(self::PARAM_ID)
            ->keyExists(self::PARAM_NAME)
            ->keyExists(self::PARAM_VOLUME)
            ->keyExists(self::PARAM_WEIGHT)
            ->verifyNow();

        Assert::lazy()->tryAll()
            ->that($payload[self::PARAM_ID], self::PARAM_ID)->notNull()->string()->uuid()
            ->that($payload[self::PARAM_NAME] ?? null, self::PARAM_NAME)->notNull()->string()->notEmpty()
            ->that(
                $payload[self::PARAM_VOLUME] ?? null,
                self::PARAM_VOLUME,
            )->notNull()->float()->greaterOrEqualThan(
                0,
            )
            ->that(
                $payload[self::PARAM_WEIGHT] ?? null,
                self::PARAM_WEIGHT,
            )->notNull()->integerish()->greaterOrEqualThan(
                0,
            )
            ->verifyNow();

        $this->id = Uuid::fromRfc4122($payload[self::PARAM_ID]);
        $this->name = (string)$payload[self::PARAM_NAME];
        $this->volume = (float)$payload[self::PARAM_VOLUME];
        $this->weight = (int)($payload[self::PARAM_WEIGHT]);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function volume(): float
    {
        return $this->volume;
    }

    public function weight(): int
    {
        return $this->weight;
    }
}
