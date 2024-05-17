<?php

namespace App\Entity;

use ArrayIterator;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\CategoryHealthRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CategoryHealthRepository::class)]
class CategoryHealth
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column(length: 100)]
    private ?string $slug = null;

    /**
     * @var Collection<int, Care>
     */
    #[ORM\OneToMany(targetEntity: Care::class, mappedBy: 'category', orphanRemoval: true)]
    private Collection $cares;

    public function __construct()
    {
        $this->cares = new ArrayCollection();
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

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
     * @return Collection<int, Care>
     */
    public function getCares(): Collection
    {
        /** @var ArrayIterator */
        $iterator = $this->cares->getIterator();
        $iterator->uasort(function($a,$b)
        {
            return strcmp($a->getName(), $b->getName());
        });
        return new ArrayCollection(iterator_to_array($iterator));
    }

    public function addCare(Care $care): static
    {
        if (!$this->cares->contains($care)) {
            $this->cares->add($care);
        }

        return $this;
    }

    public function removeCare(Care $care): static
    {
        if ($this->cares->removeElement($care)) {
            // set the owning side to null (unless already changed)
            if ($care->getCategory() === $this) {
                $care->setCategory(null);
            }
        }

        return $this;
    }
}
