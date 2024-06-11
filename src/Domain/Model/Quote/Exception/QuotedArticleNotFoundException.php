<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\Quote\Exception;

use Proyecto\Domain\Model\Shared\UserFriendlyException;
use Symfony\Component\Uid\Uuid;

//clase es una excepción si no se encuentra el artículo que se busca

class QuotedArticleNotFoundException extends UserFriendlyException
{
    public static function fromId(Uuid $id): static
    {
        return self::build(
            'Quoted Article not exists',
            'exception.quoted_article.not_found',
            [
                'quotedArticleId' => $id->toRfc4122(),
            ],
        );
    }
}
