<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\DTO\UpdatePost;
use App\Domain\Entity\AdminUserInterface;
use App\Domain\Entity\Post;
use App\Domain\Entity\PostInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

final class PostHandler implements PostHandlerInterface
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    public function create(UpdatePost $data, AdminUserInterface $adminUser): PostInterface
    {
        $post = new Post();
        $post->setUlid(new Ulid());
        $post->setTitle($data->title);
        $post->setContent($data->content);
        $post->create();
        $post->publish();

        $adminUser->addPost($post);

        return $post;
    }

    public function createForCurrentAdminUser(UpdatePost $data): PostInterface
    {
        /** @var AdminUserInterface|null $currentAdminUser */
        $currentAdminUser = $this->tokenStorage->getToken()?->getUser();
        Assert::isInstanceOf($currentAdminUser, AdminUserInterface::class);

        return $this->create($data, $currentAdminUser);
    }

    public function update(PostInterface $post, UpdatePost $data): void
    {
        $post->setTitle($data->title);
        $post->setContent($data->content);
        $post->update();
    }
}
