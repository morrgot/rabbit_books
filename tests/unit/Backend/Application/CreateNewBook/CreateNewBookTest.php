<?php

declare(strict_types=1);

namespace App\Tests\unit\Backend\Application\CreateNewBook;

use App\Backend\Application\Command\CreateNewBook\CreateNewBook;
use Codeception\Test\Unit;
use Symfony\Component\Uid\Uuid;

class CreateNewBookTest extends Unit
{
    /**
     * @dataProvider dataCreateWithInvalidTitleNegative
     */
    public function testCreateWithInvalidTitleNegative(string $title)
    {
        self::expectException(\InvalidArgumentException::class);

        (new CreateNewBook(
            Uuid::v4(),
            $title,
            'Author Name',
            100,
            (new \DateTimeImmutable())
        ));
    }

    public function dataCreateWithInvalidTitleNegative(): array
    {
        return [
            'too_long' => [str_repeat('name', 50)],
            'too_short' => [''],
            'forbidden_symbols' => ['!@#!@# '],
        ];
    }

    /**
     * @dataProvider dataCreateWithInvalidPagesNegative
     */
    public function testCreateWithInvalidPagesNegative(int $pages)
    {
        self::expectException(\InvalidArgumentException::class);

        (new CreateNewBook(
            Uuid::v4(),
            'Some Title',
            'Some Author',
            $pages,
            (new \DateTimeImmutable())
        ));
    }

    public function dataCreateWithInvalidPagesNegative(): array
    {
        return [
            'too_many' => [12314],
            'too_few' => [0],
        ];
    }

    /**
     * @dataProvider dataCreateWithInvalidAuthorNegative
     */
    public function testCreateWithInvalidAuthorNegative(string $author)
    {
        self::expectException(\InvalidArgumentException::class);

        (new CreateNewBook(
            Uuid::v4(),
            'Some title',
            $author,
            100,
            (new \DateTimeImmutable())
        ));
    }

    public function dataCreateWithInvalidAuthorNegative(): array
    {
        return [
            'too_long' => [str_repeat('name', 50)],
            'too_short' => [''],
            'forbidden_symbols' => ['!@#!@# '],
        ];
    }

    /**
     * @dataProvider dataCreateWithInvalidReleaseDateNegative
     */
    public function testCreateWithInvalidReleaseDateNegative(string $releaseDate)
    {
        self::expectException(\InvalidArgumentException::class);

        (new CreateNewBook(
            Uuid::v4(),
            'Some title',
            'Some Author',
            100,
            new \DateTimeImmutable($releaseDate)
        ));
    }

    public function dataCreateWithInvalidReleaseDateNegative(): array
    {
        return [
            'too_past' => ['1809-11-30'],
            'too_future' => ['2809-11-30'],
        ];
    }
}
