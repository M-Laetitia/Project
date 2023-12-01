<?php

namespace App\Entity;

use App\Repository\CategoryEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryEventRepository::class)]
class CategoryEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'categoryEvent', targetEntity: event::class)]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
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

    /**
     * @return Collection<int, event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCategoryEvent($this);
        }

        return $this;
    }

    public function removeEvent(event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCategoryEvent() === $this) {
                $event->setCategoryEvent(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
