<?php

namespace App\EventSubscriber;

use App\Exception\ApiException;
use App\Service\Factory\ApiResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private ApiResponseFactory $apiResponseFactory;

    public function __construct(ApiResponseFactory $apiResponseFactory)
    {
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiException) {
            $event->setResponse($this->apiResponseFactory->createErrorResponseMessage($exception->getMessage(), $exception->getCode()));
        } elseif ($exception instanceof NotFoundHttpException) {
            $event->setResponse($this->apiResponseFactory->createErrorResponseMessage($exception->getMessage(), Response::HTTP_NOT_FOUND));
        } else {
            $event->setResponse($this->apiResponseFactory->createErrorResponseMessage("Unexpected API error", Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => "onKernelException",
        ];
    }
}
