<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\AdminUser;
use App\Domain\Entity\AdminUserInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * @extends ObjectRepository<AdminUser>
 *
 * @method AdminUserInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminUserInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method array<int, AdminUserInterface> findAll()
 * @method array<int, AdminUserInterface> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface AdminUserRepositoryInterface extends ObjectRepository
{
    public function countAll(): int;
}
