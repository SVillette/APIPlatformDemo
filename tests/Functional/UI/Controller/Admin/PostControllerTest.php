<?php

declare(strict_types=1);

namespace App\Tests\Functional\UI\Controller\Admin;

use App\Domain\Repository\PostRepositoryInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Criteria;
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

    public function testUpdateAction(): void
    {
        $client = $this->loginToAdminUser();

        $postRepository = $client->getContainer()->get(PostRepositoryInterface::class);
        Assert::isInstanceOf($postRepository, PostRepositoryInterface::class);

        $latestPost = $postRepository->findBy([], ['publishedAt' => Criteria::DESC], 1)[0] ?? null;
        if (null === $latestPost) {
            self::markTestSkipped('Latest post could not be found. You should load data fixtures');
        }

        $client->request(Request::METHOD_GET, "/admin/posts/{$latestPost->getId()}/update");

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Update a post');

        $client->submitForm('Submit', [
            'post[title]' => '',
            'post[content]' => '',
        ]);

        // Data is not valid so 422 is returned.
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $client->submitForm('Submit', [
            'post[title]' => 'A title has been updated',
            'post[content]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sed ante a urna feugiat dictum. ' .
                'Fusce non commodo nisl. Morbi eget tellus cursus, efficitur lorem id, posuere eros. Pellentesque habitant.',
        ]);

        self::assertResponseRedirects();
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_admin_post_index');

        self::assertSelectorTextContains('h1', 'Posts list (49)');
        self::assertSelectorTextContains('table.table tbody tr td:first-child', 'A title has been updated');
    }

    public function testDeleteAction(): void
    {
        $client = $this->loginToAdminUser();

        $postRepository = $client->getContainer()->get(PostRepositoryInterface::class);
        Assert::isInstanceOf($postRepository, PostRepositoryInterface::class);

        $latestPost = $postRepository->findBy([], ['publishedAt' => Criteria::DESC], 1)[0] ?? null;
        if (null === $latestPost) {
            self::markTestSkipped('Latest post could not be found. You should load data fixtures');
        }

        $latestPostPublishedAt = $latestPost->getPublishedAt();
        Assert::isInstanceOf($latestPostPublishedAt, DateTimeInterface::class);

        $client->request(Request::METHOD_DELETE, "/admin/posts/{$latestPost->getId()}");

        self::assertResponseRedirects();
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_admin_post_index');

        // A post is deleted so 49 from data fixtures -1
        self::assertSelectorTextContains('h1', 'Posts list (48)');
        self::assertSelectorTextNotContains(
            'table.table tbody tr td:nth-child(2)',
            $latestPostPublishedAt->format('Y-m-d')
        );
    }
}
