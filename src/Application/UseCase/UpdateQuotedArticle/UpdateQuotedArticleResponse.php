<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\UpdateQuotedArticle;

use JsonSerializable;

class UpdateQuotedArticleResponse implements JsonSerializable
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
