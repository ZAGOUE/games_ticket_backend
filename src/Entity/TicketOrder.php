<?php

namespace App\Entity;

use App\Repository\TicketOrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketOrderRepository::class)]
class TicketOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['order:read'])]
    private ?string $order_key = null;

    #[ORM\Column(length: 50)]
    #[Groups(['order:read'])]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['order:read'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['order:read'])]
    private ?\DateTimeImmutable $validated_at = null;

    #[ORM\ManyToOne(inversedBy: 'ticketOrders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'ticketOrders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read'])]
    private ?Offer $offer = null;

    #[ORM\Column]
    #[Groups(['order:read'])]
    private ?int $quantity = null;

    #[ORM\OneToOne(inversedBy: 'ticketOrder', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)] //
    #[Groups(['order:read'])]
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderKey(): ?string
    {
        return $this->order_key;
    }

    public function setOrderKey(?string $order_key): self
    {
        $this->order_key = $order_key;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->validated_at;
    }

    public function setValidatedAt(?\DateTimeImmutable $validated_at): self
    {
        $this->validated_at = $validated_at;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;
        return $this;
    }
    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

}
