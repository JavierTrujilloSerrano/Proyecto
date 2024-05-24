<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ADMIN_ROLE = 'ROLE_ADMIN';
    public const USER_ROLE = 'ROLE_USER';

    #[ORM\Id]
    #[ORM\Column(type: "uuid", nullable: false)]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 180, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'json')]
    private array $roles;

    private function __construct(Uuid $id, string $email, string $name, array $roles)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->roles = $roles;
    }

    public static function createFromAllParams(Uuid $id, string $email, string $name, array $roles): self
    {
        return new self($id, $email, $name, $roles);
    }

    public static function build(Uuid $id, string $email, string $name, array $roles): self
    {
        return new self($id, $email, $name, $roles);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;

    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return \array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}