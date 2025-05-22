<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'order:read', 'log:read', 'payment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'order:read', 'log:read', 'payment:read'])]
    private ?string $first_name = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'order:read', 'log:read', 'payment:read'])]
    private ?string $last_name = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:read', 'order:read', 'log:read', 'payment:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe est requis.")]
    #[Assert\Length(
        min: 8,
        max: 100,
        minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le mot de passe ne doit pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/[A-Z]/",
        message: "Le mot de passe doit contenir au moins une lettre majuscule."
    )]
    #[Assert\Regex(
        pattern: "/[a-z]/",
        message: "Le mot de passe doit contenir au moins une lettre minuscule."
    )]
    #[Assert\Regex(
        pattern: "/[0-9]/",
        message: "Le mot de passe doit contenir au moins un chiffre."
    )]
    #[Assert\Regex(
        pattern: "/[@$!%*?&]/",
        message: "Le mot de passe doit contenir au moins un caractère spécial (@$!%*?&)."
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $security_key = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(nullable: true)]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int { return $this->id; }

    public function getEmail(): ?string { return $this->email; }

    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // Assure que ROLE_USER est toujours présent
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    public function setRoles(array $roles): self {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string { return $this->password; }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function getSecurityKey(): ?string { return $this->security_key; }

    public function setSecurityKey(?string $security_key): self {
        $this->security_key = $security_key;
        return $this;
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string {
        return $this->email;
    }

    public function getFirstName(): ?string { return $this->first_name; }

    public function setFirstName(string $first_name): self {
        $this->first_name = $first_name;
        return $this;
    }

    public function getLastName(): ?string { return $this->last_name; }

    public function setLastName(string $last_name): self {
        $this->last_name = $last_name;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->created_at; }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self {
        $this->created_at = $created_at;
        return $this;
    }
    public function getAdminLogs(): Collection
    {
        return $this->adminLogs;
    }

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function getTicketOrders(): Collection
    {
        return $this->ticketOrders;
    }
    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: AdminLog::class)]
    private Collection $adminLogs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Payment::class)]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TicketOrder::class)]
    private Collection $ticketOrders;

    public function __construct()
    {
        $this->adminLogs = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->ticketOrders = new ArrayCollection();
    }

}
