<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

        if ($exception instanceof ValidationException) {
            $violations = [];

            foreach ($exception->getViolations() as $violation) {
                $violations[] = [
                    'field'   => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            $event->setResponse(new JsonResponse([
                'status'     => 422,
                'error'      => 'Validation failed',
                'violations' => $violations,
            ], 422));

            return;
        }

        $statusCode = match(true) {
            $exception instanceof BadRequestHttpException => JsonResponse::HTTP_BAD_REQUEST,
            $exception instanceof NotFoundHttpException   => JsonResponse::HTTP_NOT_FOUND,
            $exception instanceof HttpExceptionInterface  => $exception->getStatusCode(),
            default                                       => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
        };

        $errorLabel = match($statusCode) {
            400     => 'Bad request',
            404     => 'Not found',
            403     => 'Forbidden',
            401     => 'Unauthorized',
            default => 'Internal server error',
        };

        $event->setResponse(new JsonResponse([
            'status'  => $statusCode,
            'error'   => $errorLabel,
            'message' => $exception->getMessage() ?: 'Une erreur inattendue est survenue. Veuillez réessayer plus tard.',
        ], $statusCode));
    }
}
