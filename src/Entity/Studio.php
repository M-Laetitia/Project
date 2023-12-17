<?php

namespace App\Entity;

use App\Repository\StudioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudioRepository::class)]
class Studio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $detail = null;

    #[ORM\Column]
    private ?int $nbRooms = null;

    #[ORM\OneToMany(mappedBy: 'studio', targetEntity: Timeslot::class)]
    private Collection $timeslots;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $picture = null;

    public function __toString() 
    {
        return $this->name; 
    }

    public function __construct()
    {
        $this->timeslots = new ArrayCollection();
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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

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

    public function getNbRegistrationMade()
    {
        return count($this->timeslots);
    }

    public function getNbRegistrationRemaining() 
    {
        return $this->nbRooms - count($this->timeslots);
    }

    /**
     * @return Collection<int, Timeslot>
     */
    public function getTimeslots(): Collection
    {
        return $this->timeslots;
    }

    public function addTimeslot(Timeslot $timeslot): static
    {
        if (!$this->timeslots->contains($timeslot)) {
            $this->timeslots->add($timeslot);
            $timeslot->setStudio($this);
        }

        return $this;
    }

    public function removeTimeslot(Timeslot $timeslot): static
    {
        if ($this->timeslots->removeElement($timeslot)) {
            // set the owning side to null (unless already changed)
            if ($timeslot->getStudio() === $this) {
                $timeslot->setStudio(null);
            }
        }

        return $this;
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
}
