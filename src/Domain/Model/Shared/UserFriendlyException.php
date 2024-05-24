<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\Shared;

use Exception;
use Throwable;

class UserFriendlyException extends Exception implements DataExceptionInterface
{
    final private function __construct(
        string $message = "",
        private readonly string $userMessage = "",
        int $code = 0,
        ?Throwable $previous = null,
        private readonly array $data = [],
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function create(
        string $message = "",
        string $userMessage = "",
        int $code = 0,
        ?Throwable $previous = null,
        array $data = [],
    ): static {
        return new static($message, $userMessage, $code, $previous, $data);
    }

    public static function build(string $message = '', string $userMessage = '', array $data = []): static
    {
        return new static($message, $userMessage, 0, null, $data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
