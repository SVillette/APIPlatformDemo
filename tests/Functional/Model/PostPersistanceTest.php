<?php

declare(strict_types=1);

namespace App\Tests\Functional\Model;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Uid\MaxUlid;

#[Group(name: 'functional')]
final class PostPersistanceTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    public function testPostIsPersistedToDB(): void
    {
        self::bootKernel();

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        self::assertInstanceOf(EntityManagerInterface::class, $entityManager);

        $clock = new MockClock();

        $now = $clock->now();

        $post = new Post();
        $post->setUlid(new MaxUlid());
        $post->setTitle('post-title');
        $post->setContent('post-content');
        $post->setPublishedAt((new MockClock('2023-05-05'))->now());
        $post->setCreatedAt($now);
        $post->setUpdatedAt($now);

        $entityManager->persist($post);
        $entityManager->flush();

        $entityManager->clear();

        $repository = $entityManager->getRepository(Post::class);
        $post = $repository->findOneBy(['title' => 'post-title']);

        self::assertNotNull($post);
        self::assertInstanceOf(Post::class, $post);

        self::assertEquals(new MaxUlid(), $post->getUlid());
        self::assertSame('post-title', $post->getTitle());
        self::assertSame('post-content', $post->getContent());
        self::assertEquals((new MockClock('2023-05-05'))->now(), $post->getPublishedAt());
        self::assertNotNull($post->getCreatedAt());
        self::assertNotNull($post->getUpdatedAt());
        self::assertEquals($now->format('Y-m-d H:i:s'), $post->getCreatedAt()->format('Y-m-d H:i:s'));
        self::assertEquals($now->format('Y-m-d H:i:s'), $post->getUpdatedAt()->format('Y-m-d H:i:s'));
    }
}
