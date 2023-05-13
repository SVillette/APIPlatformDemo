<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Application\Paginator\PaginatorInterface;
use App\Domain\DTO\PostRepresentation;
use App\Domain\Entity\Post;
use App\Domain\Entity\PostInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

/**
 * @extends ObjectRepository<Post>
 *
 * @method PostInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method array<int, PostInterface> findAll()
 * @method array<int, PostInterface> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface PostRepositoryInterface extends ObjectRepository
{
    final public const POSTS_PER_PAGE = 10;

    /**
     * @return PaginatorInterface<PostInterface>
     */
    public function findLatestPaginated(int $page = 1): PaginatorInterface;

    /**
     * @return array<int, PostRepresentation>
     */
    public function findLatest(int $page = 1): array;

    public function findLatestQueryBuilder(int $page = 1): QueryBuilder;

    public function countAll(): int;
}
