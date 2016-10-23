<?php

namespace AppBundle\EventListener;

use AppBundle\Api\ApiProblemException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if($exception instanceof ApiProblemException) {
            $problem = $exception->getApiProblem();
            $response = new JsonResponse($problem->toArray(), $problem->getStatusCode());
        } else if($exception instanceof HttpException) {
            $response = new JsonResponse([
                'type' => 'about:blank',
                'title' => $exception->getMessage(),
                'status' => $exception->getStatusCode(),
            ], $exception->getStatusCode());
        } else {
            $response = new JsonResponse([
                'type' => 'about:blank',
                'title' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        };

        $event->setResponse($response);
    }
}