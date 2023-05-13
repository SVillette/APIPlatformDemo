<?php

declare(strict_types=1);

namespace App\DTO;

use App\Domain\Entity\PostInterface;
use DateTimeInterface;
use Webmozart\Assert\Assert;

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

    public static function fromEntity(PostInterface $post): self
    {
        Assert::notNull($post->getTitle());
        Assert::notNull($post->getContent());
        Assert::notNull($post->getAuthor()?->getEmail());
        Assert::notNull($post->getPublishedAt());

        return new self(
            title: $post->getTitle(),
            content: $post->getContent(),
            author: $post->getAuthor()->getEmail(),
            publishedAt: $post->getPublishedAt(),
        );
    }
}
