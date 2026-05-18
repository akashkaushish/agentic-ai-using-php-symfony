<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        $exception = $event->getThrowable();

        $statusCode = 500;
        //$message = 'Internal server error';
        $message = $_ENV['APP_ENV'] === 'dev'
                ? $exception->getMessage()
                : 'Internal server error';

        if ($exception instanceof NotFoundHttpException) {
            $statusCode = 404;
            $message = 'Resource not found';
        }

        if ($exception instanceof BadRequestHttpException) {
            $statusCode = 400;
            $message = 'Bad request';
        }

        $response = new JsonResponse([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);

        $event->setResponse($response);
    }
}
