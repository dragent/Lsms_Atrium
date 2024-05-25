<?php

namespace App\Entity;

use App\Entity\CategoryHealth;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CareRepository;

#[ORM\Entity(repositoryClass: CareRepository::class)]
class Care
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'cares')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategoryHealth $category = null;

    #[ORM\Column(length: 100)]
    private ?string $slug = null;


    /**
     * @var Collection<int, CareSheetItem>
     */
    #[ORM\OneToMany(targetEntity: CareSheetItem::class, mappedBy: 'care')]
    private Collection $careSheetItems;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Objects $component = null;

    public function __construct()
    {
        $this->careSheetItems = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?CategoryHealth
    {
        return $this->category;
    }

    public function setCategory(?CategoryHealth $category): static
    {
        $this->category = $category;
        $this->category->addCare($this);

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
     * @return Collection<int, CareSheetItem>
     */
    public function getCareSheetItems(): Collection
    {
        return $this->careSheetItems;
    }

    public function addCareSheetItem(CareSheetItem $careSheetItem): static
    {
        if (!$this->careSheetItems->contains($careSheetItem)) {
            $this->careSheetItems->add($careSheetItem);
            $careSheetItem->setCare($this);
        }

        return $this;
    }

    public function removeCareSheetItem(CareSheetItem $careSheetItem): static
    {
        if ($this->careSheetItems->removeElement($careSheetItem)) {
            // set the owning side to null (unless already changed)
            if ($careSheetItem->getCare() === $this) {
                $careSheetItem->setCare(null);
            }
        }

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
