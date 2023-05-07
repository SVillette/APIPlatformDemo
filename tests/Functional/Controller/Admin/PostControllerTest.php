<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

#[Group('functional')]
final class PostControllerTest extends AbstractLoggedInWebTestCase
{
    public function testIndexAction(): void
    {
        $client = $this->loginToAdminUser();

        $crawler = $client->request(Request::METHOD_GET, '/admin/posts');

        self::assertResponseIsSuccessful();

        // 49 being the total items in fixtures data
        self::assertSelectorTextContains('h1', 'Posts list (49)');
        self::assertSelectorExists('ul.pagination');

        $previousPageElement = $crawler->filter('ul.pagination li.page-item')->first();
        $previousPageElementClass = $previousPageElement->attr('class');
        Assert::string($previousPageElementClass);
        self::assertStringContainsString('disabled', $previousPageElementClass);

        $nextPageElement = $crawler->filter('ul.pagination li.page-item')->last();
        $nextPageElementClass = $nextPageElement->attr('class');
        Assert::string($nextPageElementClass);
        self::assertStringNotContainsString('disabled', $nextPageElementClass);
    }

    public function testIndexActionPaginationRedirect(): void
    {
        $client = $this->loginToAdminUser();

        $client->request(Request::METHOD_GET, '/admin/posts?page=10');

        self::assertResponseRedirects();
        $crawler = $client->followRedirect();
        $request = $client->getRequest();

        self::assertRouteSame('app_admin_post_index');
        self::assertSame(5, $request->query->getInt('page'));

        $previousPageElement = $crawler->filter('ul.pagination li.page-item')->first();
        $previousPageElementClass = $previousPageElement->attr('class');
        Assert::string($previousPageElementClass);
        self::assertStringNotContainsString('disabled', $previousPageElementClass);

        $nextPageElement = $crawler->filter('ul.pagination li.page-item')->last();
        $nextPageElementClass = $nextPageElement->attr('class');
        Assert::string($nextPageElementClass);
        self::assertStringContainsString('disabled', $nextPageElementClass);
    }

    public function testCreateAction(): void
    {
        $client = $this->loginToAdminUser();

        $client->request(Request::METHOD_GET, '/admin/posts/create');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Create a new post');

        $client->submitForm('Submit');

        // Data is not valid so 422 is returned.
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $client->submitForm('Submit', [
            'post[title]' => 'Lorem ipsum dolor sit amet',
            'post[content]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sed ante a urna feugiat dictum. ' .
                'Fusce non commodo nisl. Morbi eget tellus cursus, efficitur lorem id, posuere eros. Pellentesque habitant.',
        ]);

        self::assertResponseRedirects();
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_admin_post_index');

        // A new post is created so 49 from data fixtures +1
        self::assertSelectorTextContains('h1', 'Posts list (50)');
        self::assertSelectorTextContains('table.table tbody tr td:first-child', 'Lorem ipsum dolor sit amet');
    }
}
