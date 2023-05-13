<?php

declare(strict_types=1);

namespace App\Api\StateProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Api\Paginator\DoctrineDTOPaginator;
use App\Domain\DTO\PostRepresentation;
use App\Domain\Repository\PostRepositoryInterface;

/**
 * @implements ProviderInterface<PostRepresentation>
 */
final class GetPostsProvider implements ProviderInterface
{
    public function __construct(private readonly PostRepositoryInterface $postRepository)
    {
    }

    /**
     * @param array<string, mixed> $uriVariables
     * @param array{filters?: array{page?: string}} $context
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $page = (int) ($context['filters']['page'] ?? 1);

        $items = $this->postRepository->findLatest($page);
        $itemsTotal = $this->postRepository->countAll();

        return new DoctrineDTOPaginator($items, $page, $itemsTotal, PostRepositoryInterface::POSTS_PER_PAGE);
    }
}
