<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\DTO\UpdatePost;
use App\Domain\Entity\AdminUserInterface;
use App\Domain\Entity\PostInterface;

interface PostHandlerInterface
{
    public function create(UpdatePost $data, AdminUserInterface $adminUser): PostInterface;

    public function createForCurrentAdminUser(UpdatePost $data): PostInterface;

    public function update(PostInterface $post, UpdatePost $data): void;
}
