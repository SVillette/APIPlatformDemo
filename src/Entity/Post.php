<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symandy\Component\Resource\Model\ResourceTrait;
use Symandy\Component\Resource\Model\TimestampableTrait;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(name: 'posts')]
class Post implements PostInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    #[Column(type: 'ulid', unique: true, nullable: false)]
    private ?Ulid $ulid = null;

    #[Column(type: 'string', nullable: false)]
    private ?string $title = null;

    #[Column(type: 'text', nullable: false)]
    private ?string $content = null;

    #[Column(type: 'datetime', nullable: false)]
    private ?DateTimeInterface $publishedAt = null;

    public function getUlid(): ?Ulid
    {
        return $this->ulid;
    }

    public function setUlid(?Ulid $ulid): void
    {
        $this->ulid = $ulid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeInterface $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }
}
