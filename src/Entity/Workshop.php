<?php

namespace App\Entity;

use App\Repository\WorkshopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkshopRepository::class)]
class Workshop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
        min: 150,
        max: 250,
        minMessage: "The text must contain at least {{ limit }} characters.",
        maxMessage: "The text cannot exceed {{ limit }} characters."
    )]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
        min: 400,
        minMessage: "The text must contain at least {{ limit }} characters.",
    )]
    private ?string $detail = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Please select a starting date')]
    #[Assert\GreaterThanOrEqual(
        value: "today",
        message: 'Date must be greater than or equal to current date.'
    )]
    #[Assert\When(
        expression: 'this.getEndDate() !=null',
        constraints: [
            new Assert\LessThan(
                propertyPath: 'endDate',
                message: 'The selected end date must be later than the start date.'
            )
        ]
    )]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Please select an ending date')]
    #[Assert\When(
        expression: 'this.getStartDate() != null',
        constraints: [
            new Assert\GreaterThan(
                propertyPath: 'startDate',
                message: 'The end date should later than the start date.'
            )
        ]
    )]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    #[Assert\GreaterThan(value: 0, message: 'The value must be greater than 0')]
    private ?int $nbRooms = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $picture = null;


    #[ORM\ManyToOne(inversedBy: 'workshops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // ajouter cascade persist + orphanRemovalTrue
    #[ORM\OneToMany(mappedBy: 'workshop', targetEntity: Programme::class, cascade:['persist'], orphanRemoval:true)]
    // @OrderBy({"lesson" = "ASC"})
    #[ORM\OrderBy(["lesson" => "ASC"])]
    private Collection $programmes;

    #[ORM\OneToMany(mappedBy: 'workshop', targetEntity: Registration::class)]
    private Collection $registrations;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getNbRooms(): ?int
    {
        return $this->nbRooms;
    }

    public function setNbRooms(int $nbRooms): static
    {
        $this->nbRooms = $nbRooms;

        return $this;
    }

    public function getNbRegistrationMade() {
        return count($this->registrations);
    }

    public function getNbRegistrationRemaining() {
        return $this->nbRooms - count($this->registrations);
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): static
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setWorkshop($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getWorkshop() === $this) {
                $programme->setWorkshop(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): static
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->setWorkshops($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getWorkshops() === $this) {
                $registration->setWorkshops(null);
            }
        }

        return $this;
    }
}
