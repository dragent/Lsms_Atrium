<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $accessToken = null;

    #[ORM\Column(length: 255)]
    private ?string $Username = null;

    #[ORM\Column]
    private ?string $discordId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $inService = null;

    /**
     * @var Collection<int, CareSheet>
     */
    #[ORM\OneToMany(targetEntity: CareSheet::class, mappedBy: 'medic')]
    private Collection $careSheets;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'medic')]
    private Collection $orders;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'medic', orphanRemoval: true)]
    private Collection $services;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'civil', orphanRemoval: true)]
    private Collection $civilAppointments;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'medic')]
    private Collection $medicAppointments;

    public function __construct()
    {
        $this->careSheets = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->civilAppointments = new ArrayCollection();
        $this->medicAppointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $AccessToken): static
    {
        $this->accessToken = $AccessToken;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }

    public function getDiscordId(): ?string
    {
        return $this->discordId;
    }

    public function setDiscordId(int $DiscordId): static
    {
        $this->discordId = $DiscordId;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
    public function getInService(): ?bool
    {
        return $this->inService;
    }

    public function setInService(?bool $inService): static
    {
        $this->inService = $inService;

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
            $careSheet->setMedic($this);
        }

        return $this;
    }

    public function removeCareSheet(CareSheet $careSheet): static
    {
        if ($this->careSheets->removeElement($careSheet)) {
            // set the owning side to null (unless already changed)
            if ($careSheet->getMedic() === $this) {
                $careSheet->setMedic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setMedic($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getMedic() === $this) {
                $order->setMedic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): array
    {
        $taille = sizeof($this->services);
        $services =[];
        for( $i =0 ;  $i <  15 ; $i++)
        {
            if($i>=$taille)
                break;
            $services[]=$this->services->get(key: $taille-$i-1);
        }
        return $services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setMedic($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getMedic() === $this) {
                $service->setMedic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getCivilAppointments(): Collection
    {
        return $this->civilAppointments;
    }

    public function addCivilAppointment(Appointment $civilAppointment): static
    {
        if (!$this->civilAppointments->contains($civilAppointment)) {
            $this->civilAppointments->add($civilAppointment);
            $civilAppointment->setCivil($this);
        }

        return $this;
    }

    public function removeCivilAppointment(Appointment $civilAppointment): static
    {
        if ($this->civilAppointments->removeElement($civilAppointment)) {
            // set the owning side to null (unless already changed)
            if ($civilAppointment->getCivil() === $this) {
                $civilAppointment->setCivil(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getMedicAppointments(): Collection
    {
        return $this->medicAppointments;
    }

    public function addMedicAppointment(Appointment $medicAppointment): static
    {
        if (!$this->medicAppointments->contains($medicAppointment)) {
            $this->medicAppointments->add($medicAppointment);
            $medicAppointment->setMedic($this);
        }

        return $this;
    }

    public function removeMedicAppointment(Appointment $medicAppointment): static
    {
        if ($this->medicAppointments->removeElement($medicAppointment)) {
            // set the owning side to null (unless already changed)
            if ($medicAppointment->getMedic() === $this) {
                $medicAppointment->setMedic(null);
            }
        }

        return $this;
    }

}
