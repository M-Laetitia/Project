<?php

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AreaRepository::class)]
class Area
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?int $nbRooms = null;

    #[ORM\Column(length: 30)]
    private ?string $type = null;

    #[ORM\Column(length: 30)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'area', targetEntity: ExpositionProposal::class)]
    private Collection $expositionProposals;

    #[ORM\OneToMany(mappedBy: 'area', targetEntity: AreaParticipation::class)]
    private Collection $areaParticipations;

    #[ORM\ManyToMany(targetEntity: AreaCategory::class, inversedBy: 'areas')]
    private Collection $areaCategories;



    public function __construct()
    {
        $this->expositionProposals = new ArrayCollection();
        $this->areaParticipations = new ArrayCollection();
        $this->areaCategories = new ArrayCollection();
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

    public function getNbReversationMade() {
        return count($this->areaParticipations);
    }

    public function getNbReversationRemaining() {
        return $this->nbRooms - count($this->areaParticipations);
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, ExpositionProposal>
     */
    public function getExpositionProposals(): Collection
    {
        return $this->expositionProposals;
    }

    public function addExpositionProposal(ExpositionProposal $expositionProposal): static
    {
        if (!$this->expositionProposals->contains($expositionProposal)) {
            $this->expositionProposals->add($expositionProposal);
            $expositionProposal->setArea($this);
        }

        return $this;
    }

    public function removeExpositionProposal(ExpositionProposal $expositionProposal): static
    {
        if ($this->expositionProposals->removeElement($expositionProposal)) {
            // set the owning side to null (unless already changed)
            if ($expositionProposal->getArea() === $this) {
                $expositionProposal->setArea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AreaParticipation>
     */
    public function getAreaParticipations(): Collection
    {
        return $this->areaParticipations;
    }

    public function addAreaParticipation(AreaParticipation $areaParticipation): static
    {
        if (!$this->areaParticipations->contains($areaParticipation)) {
            $this->areaParticipations->add($areaParticipation);
            $areaParticipation->setArea($this);
        }

        return $this;
    }

    public function removeAreaParticipation(AreaParticipation $areaParticipation): static
    {
        if ($this->areaParticipations->removeElement($areaParticipation)) {
            // set the owning side to null (unless already changed)
            if ($areaParticipation->getArea() === $this) {
                $areaParticipation->setArea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AreaCategory>
     */
    public function getAreaCategories(): Collection
    {
        return $this->areaCategories;
    }

    public function addAreaCategory(AreaCategory $areaCategory): static
    {
        if (!$this->areaCategories->contains($areaCategory)) {
            $this->areaCategories->add($areaCategory);
        }

        return $this;
    }

    public function removeAreaCategory(AreaCategory $areaCategory): static
    {
        $this->areaCategories->removeElement($areaCategory);

        return $this;
    }

   
}
