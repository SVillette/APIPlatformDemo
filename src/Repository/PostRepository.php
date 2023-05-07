<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

use function max;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    private const POSTS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLatest(int $page = 1): array
    {
        $currentPage = max(1, $page);
        $firstResult = ($currentPage - 1) * self::POSTS_PER_PAGE;

        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder
            ->addOrderBy('p.publishedAt', Criteria::DESC)
            ->setMaxResults(self::POSTS_PER_PAGE)
            ->setFirstResult($firstResult)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
