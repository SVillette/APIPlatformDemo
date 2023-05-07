<?php

declare(strict_types=1);

namespace App\Repository;

use App\Application\Paginator\PaginatorInterface;
use App\Entity\Post;
use App\Entity\PostInterface;
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
    /**
     * @return PaginatorInterface<PostInterface>
     */
    public function findLatestPaginated(int $page = 1): PaginatorInterface;
}
