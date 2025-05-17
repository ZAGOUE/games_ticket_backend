<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\User;
use App\Entity\TicketOrder;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['payment:read', 'order:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['payment:read', 'order:read'])]
    private ?int $amount = null;

    #[ORM\Column(length: 50)]
    #[Groups(['payment:read', 'order:read'])]
    private ?string $payment_status = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['payment:read'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['payment:read'])]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'payment', cascade: ['persist', 'remove'])]

    private ?TicketOrder $ticketOrder = null;

    public function getId(): ?int { return $this->id; }

    public function getAmount(): ?int { return $this->amount; }

    public function setAmount(int $amount): self {
        $this->amount = $amount;
        return $this;
    }

    public function getPaymentStatus(): ?string { return $this->payment_status; }

    public function setPaymentStatus(string $payment_status): self {
        $this->payment_status = $payment_status;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->created_at; }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUser(): ?User { return $this->user; }

    public function setUser(?User $user): self {
        $this->user = $user;
        return $this;
    }

    public function getTicketOrder(): ?TicketOrder { return $this->ticketOrder; }

    public function setTicketOrder(?TicketOrder $ticketOrder): self {
        $this->ticketOrder = $ticketOrder;

        if ($ticketOrder && $ticketOrder->getPayment() !== $this) {
            $ticketOrder->setPayment($this);
        }

        return $this;
    }
}
