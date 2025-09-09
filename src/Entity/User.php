<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name:"user")]
class User implements UserInterface, \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    #[Groups(["user:read"])]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:180, unique:true)]
    #[Groups(["user:read","user:write"])]
    #[Assert\NotBlank(message:"Email не должен быть пустым")]
    #[Assert\Email(message:"Неверный формат email")]
    private string $email;

    #[ORM\Column(type:"json")]
    private array $roles = [];

    #[ORM\Column(type:"string", length:255)]
    #[Groups(["user:write"])]
    #[Assert\NotBlank(message:"Пароль не должен быть пустым")]
    #[Assert\Length(min:6, minMessage:"Пароль должен быть минимум 6 символов")]
    private string $password;

    #[ORM\Column(type:"string", length:255)]
    #[Groups(["user:read","user:write"])]
    #[Assert\NotBlank(message:"Имя не должно быть пустым")]
    private string $name;

    public function getId(): ?int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getRoles(): array { return $this->roles; }
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getUserIdentifier(): string { return $this->email; }
    public function eraseCredentials(): void
    {}
}
