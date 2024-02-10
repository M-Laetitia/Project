<?php

namespace App\Entity;


use App\Repository\TimeslotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeslotRepository::class)]
class Timeslot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'timeslots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Studio $studio = null;

    #[ORM\ManyToOne(inversedBy: 'timeslots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'timeslot', targetEntity: WorkshopRegistration::class)]
    private Collection $workshopRegistrations;

    #[ORM\ManyToOne(inversedBy: 'timeslots')]
    private ?TimeSlotAvailability $timeSlotAvailability = null;

    public function __construct()
    {
        $this->workshopRegistrations = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStudio(): ?Studio
    {
        return $this->studio;
    }

    public function setStudio(?Studio $studio): static
    {
        $this->studio = $studio;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, WorkshopRegistration>
     */
    public function getWorkshopRegistrations(): Collection
    {
        return $this->workshopRegistrations;
    }

    public function getNbRegistrations() {
        return count($this->workshopRegistrations);
    }

    public function addWorkshopRegistration(WorkshopRegistration $workshopRegistration): static
    {
        if (!$this->workshopRegistrations->contains($workshopRegistration)) {
            $this->workshopRegistrations->add($workshopRegistration);
            $workshopRegistration->setTimeslot($this);
        }

        return $this;
    }

    public function removeWorkshopRegistration(WorkshopRegistration $workshopRegistration): static
    {
        if ($this->workshopRegistrations->removeElement($workshopRegistration)) {
            // set the owning side to null (unless already changed)
            if ($workshopRegistration->getTimeslot() === $this) {
                $workshopRegistration->setTimeslot(null);
            }
        }

        return $this;
    }

    public function getTimeSlotAvailability(): ?TimeSlotAvailability
    {
        return $this->timeSlotAvailability;
    }

    public function setTimeSlotAvailability(?TimeSlotAvailability $timeSlotAvailability): static
    {
        $this->timeSlotAvailability = $timeSlotAvailability;

        return $this;
    }
}
