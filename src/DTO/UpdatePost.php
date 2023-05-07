<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\PostInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class UpdatePost
{
    #[NotBlank]
    #[Length(min: 15, max: 255)]
    public ?string $title;

    #[NotBlank]
    #[Length(min: 200)]
    public ?string $content;

    public function __construct(?string $title, ?string $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    public static function fromEntity(PostInterface $post): self
    {
        return new self($post->getTitle(), $post->getContent());
    }
}
