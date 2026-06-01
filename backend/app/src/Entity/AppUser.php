<?php

namespace App\Entity;

use App\Repository\AppUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppUserRepository::class)]
#[ORM\Table(name: 'app_user')]
class AppUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_PUBLIC = 'ROLE_PUBLIC';
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[Assert\NotBlank(message: 'El id del usuario es obligatorio.')]
    #[Assert\Uuid(message: 'El id del usuario debe tener formato UUID.')]
    private ?string $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'El nombre del usuario es obligatorio.')]
    #[Assert\Length(max: 50, maxMessage: 'El nombre del usuario no puede tener más de 50 caracteres.')]
    private ?string $name = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'El primer apellido del usuario es obligatorio.')]
    #[Assert\Length(max: 50, maxMessage: 'El primer apellido del usuario no puede tener más de 50 caracteres.')]
    private ?string $first_surname = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Length(max: 50, maxMessage: 'El segundo apellido del usuario no puede tener más de 50 caracteres.')]
    private ?string $second_surname = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'El email del usuario es obligatorio.')]
    #[Assert\Length(max: 100, maxMessage: 'El email del producto no puede tener más de 100 caracteres.')]
    private ?string $email = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'La contraseña del usuario es obligatorio.')]
    #[Assert\Length(min: 8, maxMessage: 'La contraseña del usuario debe tener al menos 8 caracteres.')]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFirstSurname(): ?string
    {
        return $this->first_surname;
    }

    public function setFirstSurname(string $first_surname): void
    {
        $this->first_surname = $first_surname;
    }

    public function getSecondSurname(): ?string
    {
        return $this->second_surname;
    }

    public function setSecondSurname(string $second_surname): void
    {
        $this->second_surname = $second_surname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = self::ROLE_PUBLIC;
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $allowed = [self::ROLE_PUBLIC, self::ROLE_USER, self::ROLE_ADMIN];
        $this->roles = array_values(array_intersect($roles, $allowed));

        if (empty($this->roles)) {
            $this->roles[] = self::ROLE_PUBLIC;
        }
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // limpiar datos temporales si los hubiera
    }

}
