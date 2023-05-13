<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\ApiResource\Post;
use App\Repository\PostRepositoryInterface;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Webmozart\Assert\Assert;

#[Group('functional')]
final class PostApiResourceTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetCollectionOperation(): void
    {
        $client = self::createClient();

        $response = $client->request(Request::METHOD_GET, '/api/posts');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Post',
            '@id' => '/api/posts',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 49,
            'hydra:view' => [
                '@id' => '/api/posts?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/posts?page=1',
                'hydra:last' => '/api/posts?page=5',
                'hydra:next' => '/api/posts?page=2',
            ],
        ]);

        self::assertCount(10, $response->toArray()['hydra:member']);
        self::assertMatchesResourceCollectionJsonSchema(Post::class);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPostOperation(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_POST, '/api/posts', ['json' => [
            'title' => 'An interesting post title',
            'content' => 'This content is too short',
            'authorEmail' => 'does_not_exist@example.com',
        ]]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => "content: This value is too short. It should have 200 characters or more.\n" .
                'authorEmail: The email "does_not_exist@example.com" is not a valid author email.',
            'violations' => [
                [
                    'propertyPath' => 'content',
                    'message' => 'This value is too short. It should have 200 characters or more.',
                ],
                [
                    'propertyPath' => 'authorEmail',
                    'message' => 'The email "does_not_exist@example.com" is not a valid author email.',
                ],
            ],
        ]);

        $client->request(Request::METHOD_POST, '/api/posts', ['json' => [
            'title' => 'An interesting post title',
            'content' => 'This content is not very interesting but it is actually more than 200 characters and passing the given length constraint.' .
                'This constraint is very annoying right ? Why did I choose 200 characters ? It is too long.',
            'authorEmail' => 'john.doe@example.com',
        ]]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@type' => 'PostRepresentation',
            'title' => 'An interesting post title',
            'summary' => 'This content is not very interesting but it is act...',
            'author' => 'john.doe@example.com',
        ]);

        $postRepository = $client->getContainer()?->get(PostRepositoryInterface::class);
        Assert::isInstanceOf($postRepository, PostRepositoryInterface::class);

        $persistedPost = $postRepository->findOneBy(['title' => 'An interesting post title']);

        self::assertNotNull($persistedPost);
    }
}
