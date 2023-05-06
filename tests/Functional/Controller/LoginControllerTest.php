<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Repository\AdminUserRepositoryInterface;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

#[Group('functional')]
final class LoginControllerTest extends WebTestCase
{
    public function testAdminUrlRedirectsToAdminLogin(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, '/admin');

        self::assertResponseRedirects();
        $client->followRedirect();

        self::assertRouteSame('app_admin_login');

        $client->submitForm('Login', [
            '_username' => 'john.doe@example.com',
            '_password' => 'plain-password',
        ]);

        self::assertResponseRedirects();
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_admin_dashboard');
        self::assertSelectorTextContains('h1', 'Welcome to admin dashboard');
    }

    public function testLoggedInAdminUserCanAccessAdminDashboard(): void
    {
        $client = self::createClient();
        $adminUserRepository = self::getContainer()->get(AdminUserRepositoryInterface::class);
        self::assertInstanceOf(AdminUserRepositoryInterface::class, $adminUserRepository);

        $adminUser = $adminUserRepository->findOneBy(['email' => 'john.doe@example.com']);
        self::assertNotNull($adminUser);

        $client->loginUser($adminUser, 'admin');

        $client->request(Request::METHOD_GET, '/admin');
        self::assertResponseIsSuccessful();
    }

    public function testLoggedInAdminUserCannotAccessLoginPage(): void
    {
        $client = self::createClient();
        $adminUserRepository = self::getContainer()->get(AdminUserRepositoryInterface::class);
        self::assertInstanceOf(AdminUserRepositoryInterface::class, $adminUserRepository);

        $adminUser = $adminUserRepository->findOneBy(['email' => 'john.doe@example.com']);
        self::assertNotNull($adminUser);

        $client->loginUser($adminUser, 'admin');

        $client->request(Request::METHOD_GET, '/admin/login');

        self::assertResponseRedirects('/admin');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_admin_dashboard');
    }
}
