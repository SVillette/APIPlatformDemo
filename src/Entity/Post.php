<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symandy\Component\Resource\Model\ResourceTrait;
use Symandy\Component\Resource\Model\TimestampableTrait;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

use function date_default_timezone_get;
use function time;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(name: 'posts')]
class Post implements PostInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    #[ManyToOne(targetEntity: AdminUser::class, inversedBy: 'posts')]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?AdminUserInterface $author = null;

    #[Column(type: 'ulid', unique: true, nullable: false)]
    private ?Ulid $ulid = null;

    #[Column(type: 'string', nullable: false)]
    private ?string $title = null;

    #[Column(type: 'text', nullable: false)]
    private ?string $content = null;

    #[Column(type: 'datetime', nullable: false)]
    private ?DateTimeInterface $publishedAt = null;

    public function getAuthor(): ?AdminUserInterface
    {
        return $this->author;
    }

    public function setAuthor(?AdminUserInterface $author): void
    {
        $this->author = $author;
    }

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

    /** @noinspection PhpUnhandledExceptionInspection */
    public function publish(DateTimeZone $timezone = null): void
    {
        $timezone ??= new DateTimeZone(date_default_timezone_get());
        $publishedAt = DateTimeImmutable::createFromFormat('U', (string) time());
        Assert::isInstanceOf($publishedAt, DateTimeInterface::class);

        $this->setPublishedAt($publishedAt->setTimezone($timezone));
    }
}
