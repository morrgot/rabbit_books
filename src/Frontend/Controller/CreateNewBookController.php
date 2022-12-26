<?php

declare(strict_types=1);

namespace App\Frontend\Controller;

use App\Backend\Application\Command\CreateNewBook\CreateNewBook;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateNewBookController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {
    }

    public function __invoke(CreateNewBook $command): Response
    {
        $this->commandBus->dispatch(
            $command
        );

        return new JsonResponse(
            data: [
                'id' => $command->id->toRfc4122(),
            ],
            status: Response::HTTP_ACCEPTED
        );
    }
}
