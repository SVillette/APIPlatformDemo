<?php

declare(strict_types=1);

namespace App\Api\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\DTO\CreatePost;
use App\Domain\Entity\Post;
use App\Domain\Repository\AdminUserRepositoryInterface;
use App\DTO\PostRepresentation;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

final class CreatePostProcessor implements ProcessorInterface
{
    private ProcessorInterface $persistProcessor;

    public function __construct(
        private readonly AdminUserRepositoryInterface $adminUserRepository,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        ProcessorInterface $persistProcessor,
    ) {
        $this->persistProcessor = $persistProcessor;
    }

    /**
     * @param CreatePost $data
     * @param array<mixed> $uriVariables,
     * @param array<mixed> $context
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PostRepresentation
    {
        Assert::isInstanceOf($data, CreatePost::class);

        $adminUser = $this->adminUserRepository->findOneBy(['email' => $data->authorEmail]);

        Assert::notNull($adminUser);

        $post = new Post();
        $post->setUlid(new Ulid());
        $post->setTitle($data->title);
        $post->setContent($data->content);
        $post->create();
        $post->publish();

        $adminUser->addPost($post);

        $this->persistProcessor->process($post, $operation, $uriVariables, $context);

        return PostRepresentation::fromEntity($post);
    }
}
