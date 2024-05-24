<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\Shared;

interface DataExceptionInterface
{
    public function getData(): array;
}
