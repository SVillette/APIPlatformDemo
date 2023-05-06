<?php

declare(strict_types=1);

namespace App\Tests\Functional\Model;

use App\Entity\AdminUser;
use App\Entity\AdminUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\MockClock;

#[Group(name: 'functional')]
final class AdminUserPersistanceTest extends KernelTestCase
{
    public function testAdminUserIsPersistedIntoDB(): void
    {
        self::bootKernel();

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        self::assertInstanceOf(EntityManagerInterface::class, $entityManager);

        $clock = new MockClock();
        $now = $clock->now();

        $adminUser = new AdminUser();
        $adminUser->setEmail('john.doe@example.com');
        $adminUser->setPlainPassword('plain-password');
        self::assertNotNull($adminUser->getPlainPassword());
        $adminUser->setPassword('hashed-password');
        $adminUser->setCreatedAt($now);
        $adminUser->setUpdatedAt($now);

        $entityManager->persist($adminUser);
        $entityManager->flush();
        $entityManager->clear();

        $repository = $entityManager->getRepository(AdminUser::class);
        $adminUser = $repository->findOneBy(['email' => 'john.doe@example.com']);

        self::assertNotNull($adminUser);
        self::assertInstanceOf(AdminUserInterface::class, $adminUser);

        self::assertEquals('john.doe@example.com', $adminUser->getEmail());
        self::assertEquals('john.doe@example.com', $adminUser->getUserIdentifier());
        self::assertNull($adminUser->getPlainPassword());
        self::assertEquals('hashed-password', $adminUser->getPassword());
        self::assertNotNull($adminUser->getCreatedAt());
        self::assertNotNull($adminUser->getUpdatedAt());
        self::assertEquals($now->format('Y-m-d H:i:s'), $adminUser->getCreatedAt()->format('Y-m-d H:i:s'));
        self::assertEquals($now->format('Y-m-d H:i:s'), $adminUser->getUpdatedAt()->format('Y-m-d H:i:s'));
    }
}
