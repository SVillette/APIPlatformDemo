<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use RuntimeException;
use Symandy\Component\Resource\Model\ResourceTrait;
use Symandy\Component\Resource\Model\TimestampableTrait;

#[Entity]
#[Table('admin_users')]
class AdminUser implements AdminUserInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    #[Column(type: 'string', unique: true, nullable: false)]
    private ?string $email = null;

    private ?string $plainPassword = null;

    #[Column(type: 'string', nullable: false)]
    private ?string $password = null;

    /** @var Collection<int, PostInterface> */
    #[OneToMany(mappedBy: 'author', targetEntity: Post::class, cascade: ['persist'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return array{0: 'ROLE_ADMIN'}
     */
    public function getRoles(): array
    {
        return [self::ROLE_ADMIN];
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail() ?? throw new RuntimeException('User has null email');
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function hasPosts(): bool
    {
        return 0 < $this->posts->count();
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function hasPost(PostInterface $post): bool
    {
        return $this->posts->contains($post);
    }

    public function addPost(PostInterface $post): void
    {
        if ($this->hasPost($post)) {
            return;
        }

        $this->posts->add($post);
        $post->setAuthor($this);
    }

    public function removePost(PostInterface $post): void
    {
        if (!$this->hasPost($post)) {
            return;
        }

        $this->posts->removeElement($post);
        $post->setAuthor(null);
    }
}
