<?php

declare(strict_types=1);

namespace App\Backend\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\Table(name: "books")]
class Book
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private Uuid $id;

    #[ORM\Column(type: "string", length: 30, nullable: false)]
    private string $title;

    #[ORM\OneToOne(targetEntity: Author::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private Author $author;

    #[ORM\Column(type: "smallint", length: 1000, nullable: false, options: ['unsigned' => true])]
    private int $pages;

    #[ORM\Column(type: 'date_immutable', nullable: false)]
    private \DateTimeImmutable $releaseDate;

    public function __construct(
        Uuid $id,
        string $title,
        Author $author,
        int $pages,
        \DateTimeImmutable $releaseDate
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->pages = $pages;
        $this->releaseDate = $releaseDate;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getReleaseDate(): \DateTimeImmutable
    {
        return $this->releaseDate;
    }
}
