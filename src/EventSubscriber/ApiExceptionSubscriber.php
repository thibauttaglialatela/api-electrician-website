<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = match (true) {
            $exception instanceof NotFoundHttpException => new JsonResponse([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ], JsonResponse::HTTP_NOT_FOUND),

            $exception instanceof HttpExceptionInterface => new JsonResponse([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode()),

            default => new JsonResponse([
                'status'  => 'error',
                'message' => 'Une erreur interne est survenue',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
        };

        $event->setResponse($response);
    }
}
