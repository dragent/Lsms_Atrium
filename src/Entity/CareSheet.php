<?php

namespace App\Entity;

use App\Repository\CareSheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CareSheetRepository::class)]
class CareSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'careSheets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $medic = null;

    #[ORM\Column]
    private ?bool $isPaid = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCare = null;

    #[ORM\Column]
    private ?bool $isFarAway = null;

    #[ORM\Column]
    private ?int $invoice = null;


    /**
     * @var Collection<int, CareSheetItem>
     */
    #[ORM\OneToMany(targetEntity: CareSheetItem::class, mappedBy: 'caresheet', orphanRemoval: true)]
    private Collection $careSheetItems;

    #[ORM\ManyToOne(inversedBy: 'careSheets')]
    private ?Partner $partner = null;

    public function __construct()
    {
        $this->careSheetItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function getDateCare(): ?\DateTimeInterface
    {
        return $this->dateCare;
    }

    public function setDateCare(\DateTimeInterface $dateCare): static
    {
        $this->dateCare = $dateCare;

        return $this;
    }

    public function isFarAway(): ?bool
    {
        return $this->isFarAway;
    }

    public function setFarAway(bool $isFarAway): static
    {
        $this->isFarAway = $isFarAway;

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
            $careSheetItem->setCaresheet($this);
        }

        return $this;
    }

    public function removeCareSheetItem(CareSheetItem $careSheetItem): static
    {
        if ($this->careSheetItems->removeElement($careSheetItem)) {
            // set the owning side to null (unless already changed)
            if ($careSheetItem->getCaresheet() === $this) {
                $careSheetItem->setCaresheet(null);
            }
        }

        return $this;
    }

    public function getPartner(): ?Partner
    {
        return $this->partner;
    }

    public function setPartner(?Partner $partner): static
    {
        $this->partner = $partner;

        return $this;
    }
}
