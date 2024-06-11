<?php
declare(strict_types=1);

namespace Proyecto\Application\UseCase\DeleteQuotedArticle;

use JsonSerializable;

class DeleteQuotedArticleResponse implements JsonSerializable
{
    public function __construct(private string $name)
    {

    }

    public function name():string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return [];
    }
}
