<?php

namespace App\Entity;

use App\Repository\QuantityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuantityRepository::class)]
class Quantity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'quantitiesComponent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Objects $finalProduct = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Objects $component = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFinalProduct(): ?Objects
    {
        return $this->finalProduct;
    }

    public function setFinalProduct(?Objects $finalProduct): static
    {
        $this->finalProduct = $finalProduct;

        return $this;
    }

    public function getComponent(): ?Objects
    {
        return $this->component;
    }

    public function setComponent(?Objects $component): static
    {
        $this->component = $component;

        return $this;
    }
}
