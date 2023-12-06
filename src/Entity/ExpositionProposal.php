<?php

namespace App\Entity;

use App\Repository\ExpositionProposalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpositionProposalRepository::class)]
class ExpositionProposal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $proposalDate = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'expositionProposals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'expositionProposals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Area $area = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProposalDate(): ?\DateTimeInterface
    {
        return $this->proposalDate;
    }

    public function setProposalDate(\DateTimeInterface $proposalDate): static
    {
        $this->proposalDate = $proposalDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

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

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): static
    {
        $this->area = $area;

        return $this;
    }


}
