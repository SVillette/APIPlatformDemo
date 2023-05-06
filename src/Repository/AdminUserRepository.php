<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdminUser;
use App\Entity\AdminUserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminUserInterface>
 */
class AdminUserRepository extends ServiceEntityRepository implements AdminUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminUser::class);
    }
}
