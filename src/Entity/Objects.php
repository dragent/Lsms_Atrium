<?php

namespace App\Entity;

use App\Repository\ObjectsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjectsRepository::class)]
class Objects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $quantityTrigger = null;

    #[ORM\Column(nullable: true)]
    private ?int $buyPrice = null;

    #[ORM\Column(nullable: true)]
    private ?int $sellPrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantityTrigger(): ?int
    {
        return $this->quantityTrigger;
    }

    public function setQuantityTrigger(int $quantityTrigger): static
    {
        $this->quantityTrigger = $quantityTrigger;

        return $this;
    }

    public function getBuyPrice(): ?int
    {
        return $this->buyPrice;
    }

    public function setBuyPrice(int $buyPrice): static
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    public function getSellPrice(): ?int
    {
        return $this->sellPrice;
    }

    public function setSellPrice(?int $sellPrice): static
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }
}
