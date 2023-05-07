<?php

namespace App\Tests\Functional\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;

class DashboardControllerTest extends AbstractLoggedInWebTestCase
{
    public function testIndexAction(): void
    {
        $client = $this->loginToAdminUser();

        $client->request(Request::METHOD_GET, '/admin');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_admin_dashboard');

        self::assertSelectorTextContains('card:first-child span.fs-1', '49 POSTS');
        self::assertSelectorTextContains('card:second-child span.fs-1', '1 ADMIN USER');
    }
}
