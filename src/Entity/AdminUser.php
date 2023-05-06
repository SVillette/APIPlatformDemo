<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
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

    #[Column(type: 'string', nullable: false)]
    private ?string $email = null;

    private ?string $plainPassword = null;

    #[Column(type: 'string', nullable: false)]
    private ?string $password = null;

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
}
