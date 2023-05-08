<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeInterface;

use function substr;

final class PostRepresentation
{
    public readonly string $summary;

    public function __construct(
        public readonly string $title,
        string $content,
        public readonly string $author,
        public readonly DateTimeInterface $publishedAt,
    ) {
        $this->summary = substr($content, 0, 50) . '...';
    }
}
