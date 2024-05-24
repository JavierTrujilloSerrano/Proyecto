<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\DeleteQuotedArticle;

use JsonSerializable;

class DeleteQuotedArticleResponse implements JsonSerializable
{
    public function __construct()
    {
        // intentionally left blank
    }

    public function jsonSerialize(): array
    {
        return [];
    }
}
