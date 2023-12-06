<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LessonCategory $lessonCategory = null;

    #[ORM\OneToMany(mappedBy: 'lesson', targetEntity: Programme::class)]
    private Collection $programmes;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
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

    public function getLessonCategory(): ?LessonCategory
    {
        return $this->lessonCategory;
    }

    public function setLessonCategory(?LessonCategory $lessonCategory): static
    {
        $this->lessonCategory = $lessonCategory;

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
            $programme->setLesson($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getLesson() === $this) {
                $programme->setLesson(null);
            }
        }

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
}
