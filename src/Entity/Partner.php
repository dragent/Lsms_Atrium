<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, CareSheet>
     */
    #[ORM\OneToMany(targetEntity: CareSheet::class, mappedBy: 'partner')]
    private Collection $careSheets;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->careSheets = new ArrayCollection();
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

    /**
     * @return Collection<int, CareSheet>
     */
    public function getCareSheets(): Collection
    {
        return $this->careSheets;
    }

    public function addCareSheet(CareSheet $careSheet): static
    {
        if (!$this->careSheets->contains($careSheet)) {
            $this->careSheets->add($careSheet);
            $careSheet->setPartner($this);
        }

        return $this;
    }

    public function removeCareSheet(CareSheet $careSheet): static
    {
        if ($this->careSheets->removeElement($careSheet)) {
            // set the owning side to null (unless already changed)
            if ($careSheet->getPartner() === $this) {
                $careSheet->setPartner(null);
            }
        }

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
}
