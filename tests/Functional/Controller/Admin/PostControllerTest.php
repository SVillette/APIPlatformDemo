<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Request;

#[Group('functional')]
final class PostControllerTest extends AbstractLoggedInWebTestCase
{
    public function testIndexAction(): void
    {
        $client = $this->loginToAdminUser();

        $client->request(Request::METHOD_GET, '/admin/posts');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Posts list');
    }
}
