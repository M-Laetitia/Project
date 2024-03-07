<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $paymentDate = null;

    #[ORM\Column]
    private array $infosUser = [];

    #[ORM\Column]
    private array $infosSubscription = [];

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $total = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'subscription')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SubscriptionType $subscriptionType = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeInterface $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getInfosUser(): array
    {
        return $this->infosUser;
    }

    public function setInfosUser(array $infosUser): static
    {
        $this->infosUser = $infosUser;

        return $this;
    }

    public function getInfosSubscription(): array
    {
        return $this->infosSubscription;
    }

    public function setInfosSubscription(array $infosSubscription): static
    {
        $this->infosSubscription = $infosSubscription;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

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

    public function getSubscriptionType(): ?SubscriptionType
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType(?SubscriptionType $subscriptionType): static
    {
        $this->subscriptionType = $subscriptionType;

        return $this;
    }

    public function getDaysRemaining(): array
    {
        $duration = $this->subscriptionType->getDuration();
        // creates an independent copy of the $this->paymentDate object. This ensures that any subsequent operations, like adding the subscription duration to calculate the expiration date, are performed on the copy without altering the original payment date. 
        // Creates an independent copy of the $this->paymentDate object.
        $paymentDate = clone $this->paymentDate;

        // adds a specified duration (in days) to the $expirationDate object. It uses DateInterval to represent the duration, where 'P' indicates the period and 'D' denotes days. 

       // Adds the subscription duration to the payment date.
        $expirationDate = $paymentDate->add(new \DateInterval('P' . $duration . 'D'));
        
       // Use DateTimeImmutable to avoid unintended modifications.
        $currentDate = new \DateTimeImmutable();

        if ($currentDate > $expirationDate) {
            // Subscription has already expired.
            return [
                'endDate' => 'Subscription ended',
                'remaining' => 0
            ];
        }
        

        $remainingDays = $currentDate->diff($expirationDate)->days;

        return [
            'endDate' => $expirationDate->format('d-m-Y'),
            'remaining' => $remainingDays
        ];

    }

    public function getEndDate()
    {
        $duration = $this->subscriptionType->getDuration();
        $paymentDate = clone $this->paymentDate;
        $expirationDate = $paymentDate->add(new \DateInterval('P' . $duration . 'D'));
        $currentDate = new \DateTimeImmutable();
        return $expirationDate;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    // public function updateIsActive(): void;
    // {
    //     $currentDate= new \DateTimeImmutable();
    //     $experiationDate = clone $this->paymentDate;
    //     $experiationDate->add(new \DateInterval('P' . $this->subscriptionType->getDuration() . 'D'));

    //     $this->isActive = $currentDate <= $expirationsDate;

    // }



}
