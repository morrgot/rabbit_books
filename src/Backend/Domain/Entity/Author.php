<?php

declare(strict_types=1);

namespace App\Backend\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\Table(name: "authors")]
class Author
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private Uuid $id;

    #[ORM\Column(type: "string", length: 30, nullable: false)]
    private string $name;

    public function __construct(Uuid $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
