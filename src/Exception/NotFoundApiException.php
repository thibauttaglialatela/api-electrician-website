<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundApiException extends NotFoundHttpException
{
    public function __construct(string $entityName = 'Ressource')
    {
        parent::__construct(\sprintf('%s introuvable', $entityName));
    }
}
