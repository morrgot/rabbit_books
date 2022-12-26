<?php

declare(strict_types=1);

namespace App\Backend\Application\Query\GetBooksList;

use Symfony\Component\Uid\Uuid;

class BookListItem implements \JsonSerializable
{
    public function __construct(
        public readonly Uuid $id,
        public readonly string $title,
        public readonly string $author,
        public readonly int $pages,
        public readonly \DateTimeImmutable $releaseDate,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->toRfc4122(),
            'title' => $this->title,
            'author' => $this->author,
            'pages' => $this->pages,
            'releaseDate' => $this->releaseDate->format('Y-m-d'),
        ];
    }
}
