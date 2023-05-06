<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Symandy\Component\Resource\Model\ResourceInterface;
use Symandy\Component\Resource\Model\TimestampableInterface;
use Symfony\Component\Uid\Ulid;

interface PostInterface extends ResourceInterface, TimestampableInterface
{
    public function getAuthor(): ?AdminUserInterface;

    public function setAuthor(?AdminUserInterface $author): void;

    public function getUlid(): ?Ulid;

    public function setUlid(?Ulid $ulid): void;

    public function getTitle(): ?string;

    public function setTitle(?string $title): void;

    public function getContent(): ?string;

    public function setContent(?string $content): void;

    public function getPublishedAt(): ?DateTimeInterface;

    public function setPublishedAt(?DateTimeInterface $publishedAt): void;
}
