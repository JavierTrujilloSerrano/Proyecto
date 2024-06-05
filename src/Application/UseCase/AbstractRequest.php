<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase;

use Symfony\Component\Uid\Uuid;

abstract class AbstractRequest implements AbstractRequestInterface
{
    final protected function __construct(private readonly Uuid $messageId, private readonly array $payload)
    {
        $this->assertPrimitivePayload($payload);
    }

    final public static function fromPayload(Uuid $messageId, array $payload): static
    {
        $message = new static($messageId, $payload);
        $message->assertPayload();

        return $message;
    }

    public function delay(): int
    {
        return 0;
    }

    public function messageId(): Uuid
    {
        return $this->messageId;
    }

    public function messagePayload(): array
    {
        return $this->payload;
    }

    public function jsonSerialize(): array
    {
        return [
            'message_id' => $this->messageId(),
            'name' => static::messageName(),
            'payload' => $this->messagePayload(),
        ];
    }

    abstract public static function messageName(): string;

    abstract protected function assertPayload(): void;

    private function assertPrimitivePayload(array &$payload, string $index = 'payload'): void
    {
        \array_walk(
            $payload,
            function ($item, $currentIndex) use ($index) {
                $newIndex = "{$index}.{$currentIndex}";

                if (true === \is_object($item)) {
                    throw new \InvalidArgumentException(
                        \sprintf(
                            'Attribute %s is an object. Payload parameters only can be primitive.',
                            $newIndex,
                        ),
                    );
                }

                if (true !== \is_array($item)) {
                    return;
                }

                $this->assertPrimitivePayload($item, $newIndex);
            },
        );
    }
}
