<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symandy\Component\Resource\Model\ResourceInterface;
use Symandy\Component\Resource\Model\TimestampableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface AdminUserInterface extends ResourceInterface, TimestampableInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    final public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function getEmail(): ?string;

    public function setEmail(?string $email): void;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function hasPosts(): bool;

    /**
     * @return Collection<int, PostInterface>
     */
    public function getPosts(): Collection;

    public function hasPost(PostInterface $post): bool;

    public function addPost(PostInterface $post): void;

    public function removePost(PostInterface $post): void;
}
