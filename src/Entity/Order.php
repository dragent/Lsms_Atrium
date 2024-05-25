<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $orderAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $receiveAt = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $medic = null;

    #[ORM\Column]
    private ?int $invoice = null;

    /**
     * @var Collection<int, OrderItems>
     */
    #[ORM\OneToMany(targetEntity: OrderItems::class, mappedBy: 'orderLink')]
    private Collection $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderAt(): ?\DateTimeImmutable
    {
        return $this->orderAt;
    }

    public function setOrderAt(\DateTimeImmutable $orderAt): static
    {
        $this->orderAt = $orderAt;

        return $this;
    }

    public function getReceiveAt(): ?\DateTimeImmutable
    {
        return $this->receiveAt;
    }

    public function setReceiveAt(?\DateTimeImmutable $receiveAt): static
    {
        $this->receiveAt = $receiveAt;

        return $this;
    }

    public function getMedic(): ?User
    {
        return $this->medic;
    }

    public function setMedic(?User $medic): static
    {
        $this->medic = $medic;

        return $this;
    }

    public function getInvoice(): ?int
    {
        return $this->invoice;
    }

    public function setInvoice(int $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @return Collection<int, OrderItems>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItems $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrderLink($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItems $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrderLink() === $this) {
                $orderItem->setOrderLink(null);
            }
        }

        return $this;
    }
}
