<?php

declare(strict_types=1);

namespace App\Api\DTO;

use App\Validator\Constraints\AdminUserEmail;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CreatePost
{
    #[NotBlank]
    #[Length(min: 15, max: 255)]
    public ?string $title;

    #[NotBlank]
    #[Length(min: 200)]
    public ?string $content;

    #[NotBlank]
    #[Email]
    #[AdminUserEmail]
    public ?string $authorEmail;

    public function __construct(?string $title, ?string $content, ?string $authorEmail)
    {
        $this->title = $title;
        $this->content = $content;
        $this->authorEmail = $authorEmail;
    }
}
