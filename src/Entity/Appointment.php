<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $appointmentAt = null;

    #[ORM\ManyToOne(inversedBy: 'civilAppointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $civil = null;

    #[ORM\ManyToOne(inversedBy: 'medicAppointments')]
    private ?User $medic = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppointmentAt(): ?\DateTimeImmutable
    {
        return $this->appointmentAt;
    }

    public function setAppointmentAt(?\DateTimeImmutable $appointmentAt): static
    {
        $this->appointmentAt = $appointmentAt;

        return $this;
    }

    public function getCivil(): ?User
    {
        return $this->civil;
    }

    public function setCivil(?User $civil): static
    {
        $this->civil = $civil;

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

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }
}
