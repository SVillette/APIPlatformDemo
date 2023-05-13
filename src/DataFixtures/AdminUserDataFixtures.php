<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Entity\AdminUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminUserDataFixtures extends Fixture
{
    final public const REFERENCE_ADMIN_USER_1 = 'admin-user-1';

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $plainPassword = 'plain-password';

        $adminUser = new AdminUser();
        $adminUser->setEmail('john.doe@example.com');
        $adminUser->setPlainPassword($plainPassword);
        $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, $plainPassword));
        $adminUser->create();

        $this->addReference(self::REFERENCE_ADMIN_USER_1, $adminUser);

        $manager->persist($adminUser);
        $manager->flush();
    }
}
