<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use App\Domain\Entity\AdminUserInterface;
use App\Domain\Entity\Post;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

use function dirname;
use function postGenerator;

require_once dirname(__DIR__) . '../../data/fixtures/posts-data.php';

final class PostFixtures extends Fixture
{
    private const LAST_PUBLISHED_AT = '2023-04-31';

    private DateTimeImmutable $currentPublishedAt;

    private DateTimeImmutable $lastPublishedAt;

    public function load(ObjectManager $manager): void
    {
        $publishedAt = $this->getNextPublishedAt();
        Assert::notNull($publishedAt);

        $postDataGenerator = postGenerator();
        $postData = $postDataGenerator->current();
        $reference = $postDataGenerator->key();

        do {
            Assert::isArray($postData);
            Assert::string($reference);

            $post = new Post();
            $post->setUlid(new Ulid());
            $post->setTitle($postData['title']);
            $post->setContent($postData['content']);
            $post->setPublishedAt($publishedAt);
            $post->setCreatedAt($publishedAt);
            $post->setUpdatedAt($publishedAt);

            $author = $this->getReference(AdminUserDataFixtures::REFERENCE_ADMIN_USER_1);
            Assert::isInstanceOf($author, AdminUserInterface::class);

            $author->addPost($post);

            $manager->persist($post);

            $this->addReference($reference, $post);

            $publishedAt = $this->getNextPublishedAt();

            $postDataGenerator->next();
            $postData = $postDataGenerator->current();
            $reference = $postDataGenerator->key();
        } while (null !== $publishedAt && null !== $postData);

        $manager->flush();
    }

    private function getNextPublishedAt(): ?DateTimeImmutable
    {
        $this->currentPublishedAt ??= new DateTimeImmutable('2022-01-01');
        $this->lastPublishedAt ??= new DateTimeImmutable(self::LAST_PUBLISHED_AT);

        $publishedAt = $this->currentPublishedAt->modify('+1 week');

        if ($publishedAt > $this->lastPublishedAt) {
            return null;
        }

        $this->currentPublishedAt = $publishedAt;

        return $publishedAt;
    }
}
