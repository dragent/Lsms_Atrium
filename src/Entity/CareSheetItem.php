<?php

namespace App\Entity;

use App\Repository\CareSheetItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CareSheetItemRepository::class)]
class CareSheetItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'careSheetItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Caresheet $caresheet = null;

    #[ORM\ManyToOne(inversedBy: 'careSheetItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Care $care = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaresheet(): ?Caresheet
    {
        return $this->caresheet;
    }

    public function setCaresheet(?Caresheet $caresheet): static
    {
        $this->caresheet = $caresheet;

        return $this;
    }

    public function getCare(): ?Care
    {
        return $this->care;
    }

    public function setCare(?Care $care): static
    {
        $this->care = $care;

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
}
