<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\AdminUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminUser>
 */
class AdminUserRepository extends ServiceEntityRepository implements AdminUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminUser::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAll(): int
    {
        $queryBuilder = $this->createQueryBuilder('au');

        $queryBuilder->select([$queryBuilder->expr()->count('au')]);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
