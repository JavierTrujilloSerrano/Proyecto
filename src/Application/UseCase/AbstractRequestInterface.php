<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase;

use Symfony\Component\Uid\Uuid;

interface AbstractRequestInterface extends \JsonSerializable
{
    public static function fromPayload(Uuid $messageId, array $payload): static;

    public function messageId(): Uuid;

    public function messagePayload(): array;

    public function delay(): int;

    public static function messageName(): string;
}
