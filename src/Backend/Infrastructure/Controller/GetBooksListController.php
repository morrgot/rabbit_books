<?php

declare(strict_types=1);

namespace App\Backend\Infrastructure\Controller;

use App\Backend\Application\Query\GetBooksList\GetBooksList;
use App\Backend\Application\Query\GetBooksList\GetBooksListHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetBooksListController
{
    public function __construct(
        private readonly GetBooksListHandler $getBooksListHandler
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $query = new GetBooksList();
        $books = call_user_func($this->getBooksListHandler, $query);

        return new JsonResponse(
            data: ['data' => $books]
        );
    }
}
