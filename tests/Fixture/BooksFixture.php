<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Backend\Domain\Entity\Author;
use App\Backend\Domain\Entity\Book;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class BooksFixture implements FixtureInterface
{
    /**
     * @var array<Book>
     */
    private array $books;

    public function __construct(array $booksDefinitions)
    {
        foreach ($booksDefinitions as $book) {
            $this->books[] = new Book(
                self::uuidFromMixed($book['id'] ?? null),
                $book['title'],
                new Author(
                    self::uuidFromMixed(null),
                    $book['author'] ?? 'some author name'
                ),
                (int) ($book['pages'] ?? 100),
                \DateTimeImmutable::createFromFormat('Y-m-d', (string) $book['releaseDate'])
            );
        }
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->books as $book) {
            $manager->persist($book);
        }

        $manager->flush();
    }

    private static function uuidFromMixed(Uuid|string|null $value): Uuid
    {
        return match(true) {
            is_string($value) => Uuid::fromString($value),
            $value instanceof Uuid => $value,
            $value === null => Uuid::v4(),
        };
    }
}
