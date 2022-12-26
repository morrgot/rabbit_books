<?php

declare(strict_types=1);

namespace App\Backend\Application\Command\CreateNewBook;

use Symfony\Component\Uid\Uuid;

class CreateNewBook
{
    private const PATTERN = '/^[A-Za-z0-9 \.]{1,30}$/';

    public function __construct(
        public readonly Uuid $id,
        public readonly string $title,
        public readonly string $author,
        public readonly int $pages,
        public readonly \DateTimeImmutable $releaseDate,
    ) {
        if ($pages <= 0 || $pages > 1000) {
            throw new \InvalidArgumentException('"pages" must be between 1 and 1000');
        }

        if (!preg_match(self::PATTERN, $title)) {
            throw new \InvalidArgumentException('Title must match pattern ' . self::PATTERN);
        }

        if (!preg_match(self::PATTERN, $author)) {
            throw new \InvalidArgumentException('Author must match pattern ' . self::PATTERN);
        }

        $year = (int) $releaseDate->format('Y');
        $nowYear = (int) (new \DateTimeImmutable())->format('Y');
        if ($year < ($nowYear - 100) || $year > ($nowYear + 100)) {
            throw new \InvalidArgumentException('Year must be +/- ' . $nowYear);
        }
    }
}
