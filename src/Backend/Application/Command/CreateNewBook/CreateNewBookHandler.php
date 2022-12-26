<?php

declare(strict_types=1);

namespace App\Backend\Application\Command\CreateNewBook;

use App\Backend\Domain\Entity\Author;
use App\Backend\Domain\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class CreateNewBookHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateNewBook $command): void
    {
        $authorId = Uuid::v4();

        $book = new Book(
            $command->id,
            $command->title,
            new Author($authorId, $command->author),
            $command->pages,
            $command->releaseDate,
        );

        $this->entityManager->persist($book);
    }
}
