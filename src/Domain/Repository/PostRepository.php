<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Application\Paginator\DoctrineORMPaginator;
use App\Application\Paginator\PaginatorInterface;
use App\Domain\DTO\PostRepresentation;
use App\Domain\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

use function max;
use function sprintf;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLatestPaginated(int $page = 1): PaginatorInterface
    {
        $queryBuilder = $this->findLatestQueryBuilder($page);

        return new DoctrineORMPaginator($queryBuilder->getQuery(), $page);
    }

    public function findLatest(int $page = 1): array
    {
        $queryBuilder = $this->findLatestQueryBuilder($page);

        $queryBuilder
            ->select(sprintf('new %s(p.title, p.content, a.email, p.publishedAt)', PostRepresentation::class))
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function findLatestQueryBuilder(int $page = 1): QueryBuilder
    {
        $currentPage = max(1, $page);
        $firstResult = ($currentPage - 1) * self::POSTS_PER_PAGE;

        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder
            ->innerJoin('p.author', 'a')
            ->addOrderBy('p.publishedAt', Criteria::DESC)
            ->setMaxResults(self::POSTS_PER_PAGE)
            ->setFirstResult($firstResult)
        ;

        return $queryBuilder;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAll(): int
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->select([$queryBuilder->expr()->count('p')]);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
