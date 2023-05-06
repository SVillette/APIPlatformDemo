<?php

declare(strict_types=1);

namespace App\Entity;

use Symandy\Component\Resource\Model\ResourceInterface;
use Symandy\Component\Resource\Model\TimestampableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface AdminUserInterface extends ResourceInterface, TimestampableInterface, PasswordAuthenticatedUserInterface
{
    final public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function getEmail(): ?string;

    public function setEmail(?string $email): void;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;
}
