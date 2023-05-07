<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Repository\AdminUserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractLoggedInWebTestCase extends WebTestCase
{
    protected function loginToAdminUser(): KernelBrowser
    {
        $client = self::createClient();
        $adminUserRepository = self::getContainer()->get(AdminUserRepositoryInterface::class);
        self::assertInstanceOf(AdminUserRepositoryInterface::class, $adminUserRepository);

        $adminUser = $adminUserRepository->findOneBy(['email' => 'john.doe@example.com']);
        self::assertNotNull($adminUser);

        $client->loginUser($adminUser, 'admin');

        return $client;
    }
}
