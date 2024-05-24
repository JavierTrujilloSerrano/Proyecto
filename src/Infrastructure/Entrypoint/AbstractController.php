<?php
declare(strict_types=1);

namespace Proyecto\Infrastructure\Entrypoint;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends SymfonyAbstractController
{
    public function getRequestBody(Request $request): array
    {
        return (array)\json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
    }
    
}
