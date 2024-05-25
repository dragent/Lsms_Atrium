<?php

namespace App\Entity;

use App\Repository\ObjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ObjectsRepository::class)]
class Objects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 3,
        minMessage: "Vous devez mettre un nom de produit de 4 lettre"
    )]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank]   
    #[Assert\PositiveOrZero(
        message: "La quantité ne peut pas être négative"
    )]
    private ?int $quantity = null;

    #[ORM\Column]
    #[Assert\NotBlank]   
    #[Assert\Positive(
        message: "Le seuil d'avertissement doit être supérieur à un"
    )]
    private ?int $quantityTrigger = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero(
        message: "Le prix ne peut pas être négatif"
    )]
    private ?int $buyPrice = null;

    #[ORM\Column(length: 100,nullable: true)]
    private ?string $slug = null;

    /**
     * @var Collection<int, Quantity>
     */
    #[ORM\OneToMany(targetEntity: Quantity::class, mappedBy: 'finalProduct', orphanRemoval: true, cascade:['persist'])]
    private Collection $quantitiesComponent;

    public function __construct()
    {
        $this->quantitiesComponent = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Quantity>
     */
    public function getQuantitiesComponent(): Collection
    {
        return $this->quantitiesComponent;
    }

    public function addQuantitiesComponent(Quantity $quantitiesComponent): static
    {
        if (!$this->quantitiesComponent->contains($quantitiesComponent)) {
            $this->quantitiesComponent->add($quantitiesComponent);
            $quantitiesComponent->setFinalProduct($this);
        }

        return $this;
    }

    public function removeQuantitiesComponent(Quantity $quantitiesComponent): static
    {
        if ($this->quantitiesComponent->removeElement($quantitiesComponent)) {
            // set the owning side to null (unless already changed)
            if ($quantitiesComponent->getFinalProduct() === $this) {
                $quantitiesComponent->setFinalProduct(null);
            }
        }

        return $this;
    }

}
