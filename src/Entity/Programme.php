<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $StartDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $EndDate = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workshop $workshop = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lesson $lesson = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->StartDate;
    }

    public function setStartDate(\DateTimeInterface $StartDate): static
    {
        $this->StartDate = $StartDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->EndDate;
    }

    public function setEndDate(\DateTimeInterface $EndDate): static
    {
        $this->EndDate = $EndDate;

        return $this;
    }

    public function getWorkshop(): ?Workshop
    {
        return $this->workshop;
    }

    public function setWorkshop(?Workshop $workshop): static
    {
        $this->workshop = $workshop;

        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    // public function getTotalDuration(): string
    // {
    //     $totalMinutes = 0;
    //     foreach ($this->lessons as $lesson) {
    //         $startDate = $lesson->getStartDate();
    //         $endDate = $lesson->getEndDate();
    //         $diff = $endDate->getTimestamp() - $startDate->getTimestamp();
    //         $totalMinutes += $diff / 60; // Convertit la différence en minutes
    //     }
        
    //     // Convertir le total de minutes en heures et minutes
    //     $hours = floor($totalMinutes / 60);
    //     $minutes = $totalMinutes % 60;

    //     // Retourner le temps total au format heures:minutes
    //     return sprintf("%02d:%02d", $hours, $minutes);
    // }
  

}
