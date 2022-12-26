<?php

declare(strict_types=1);

namespace App\Backend\Application\Query\GetBooksList;

use App\Backend\Domain\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

class GetBooksListHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<BookListItem>
     */
    public function __invoke(GetBooksList $query): array
    {
        $bookCollection = $this->entityManager->getRepository(Book::class)
            ->findBy(
                [],
                ['releaseDate' => 'desc']
            );

        $arr = [];
        foreach ($bookCollection as $book) {
            $arr[] = new BookListItem(
                $book->getId(),
                $book->getTitle(),
                $book->getAuthor()->getName(),
                $book->getPages(),
                $book->getReleaseDate(),
            );
        }

        return $arr;
    }
}
