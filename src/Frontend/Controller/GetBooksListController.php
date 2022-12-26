<?php

declare(strict_types=1);

namespace App\Frontend\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetBooksListController extends AbstractController
{
    public function getBooks(Request $request): Response
    {
        $response = $this->forward(
            \App\Backend\Infrastructure\Controller\GetBooksListController::class
        );

        return $response;
    }
}
