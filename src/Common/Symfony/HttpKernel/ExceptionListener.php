<?php

declare(strict_types=1);

namespace App\Common\Symfony\HttpKernel;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            if (!empty($exception->getMessage())) {
                $response->setContent(
                    \json_encode([
                        'error' => $exception->getMessage()
                    ])
                );
            }

            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $response->setContent(
                \json_encode([
                    'class' => get_class($exception),
                    'message' => $exception->getMessage(),
                ])
            );
            $this->logger->critical(
                sprintf('[Controller] Unhandled exception'),
                ['exception' => $exception]
            );
        }

        $event->setResponse($response);
    }
}
