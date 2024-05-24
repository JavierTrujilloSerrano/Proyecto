<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\CreateQuotedArticle;

use JsonSerializable;

class CreateQuotedArticleResponse implements JsonSerializable
{
    private function __construct()
    {
        // Left intentionally blank
    }

    public static function build(): self
    {
        return new self();
    }

    public function jsonSerialize(): array
    {
        return [];
    }
}
