<?php

declare(strict_types=1);

namespace App\Repository;

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
     * @return array<int, PostInterface>
     */
    public function findLatest(int $page = 1): array;
}
