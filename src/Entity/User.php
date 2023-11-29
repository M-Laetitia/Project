<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE , nullable: true)]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLoginDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastProfilEditDate = null;

    
    #[ORM\PrePersist]
    public function addRegistrationDate() : void
    {
        $this->registrationDate = new \DateTimeImmutable();
    }


    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->lastProfilEditDate = new \DateTime();

    }

     /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->lastProfilEditDate = new \DateTime();
   
    }


    /**
     * 
     */
    public function updateLastLogin()
    {
        $this->lastLoginDate = new \DateTime();
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSimpleRoles(): array
    {
        // fonction utiliser pour filtrer les éléments du tableau. Elle prend deux arguments : le tableau à filtrer ($this->getRoles() dans ce cas) et une fonction de rappel qui définit la condition de filtrage.
        $filteredRoles = array_filter($this->getRoles(), function ($roles) {
            // retourne true pour conserver un rôle et false pour l'exclure. Dans ce cas, on exclut le rôle "ROLE_USER".
            return $roles !== 'ROLE_USER';
        });

        // applique une fonction donnée à chaque élément d'un tableau et retourne un nouveau tableau avec les résultats.
        $simpleRoles = array_map(function ($roles) {

            // utilisée pour formater chaque rôle restant. Elle convertit d'abord le rôle en minuscules (strtolower), puis retire le préfixe "ROLE_" et remplace les underscores par des espaces.
            
            return strtolower(str_replace(['ROLE_', '_'], ['', ' '], $roles));
        }, $filteredRoles);

        return $simpleRoles;
    }


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }



    // private $oldPassword;

    // // Propriété pour stocker le nouveau mot de passe
    // private $newPassword;

    // // Méthode pour obtenir l'ancien mot de passe
    // public function getOldPassword(): ?string
    // {
    //     return $this->oldPassword;
    // }

    // // Méthode pour définir l'ancien mot de passe
    // public function setOldPassword(string $oldPassword): self
    // {
    //     $this->oldPassword = $oldPassword;
    //     return $this;
    // }

    // // Méthode pour obtenir le nouveau mot de passe
    // public function getNewPassword(): ?string
    // {
    //     return $this->newPassword;
    // }

    // // Méthode pour définir le nouveau mot de passe
    // public function setNewPassword(string $newPassword): self
    // {
    //     $this->newPassword = $newPassword;
    //     return $this;
    // }






    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    
    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }
    
    public function setRegistrationDate(\DateTimeInterface $registrationDate): static
    {
        $this->registrationDate = $registrationDate;
        
        return $this;
    }


    public function getDurationSinceRegistration() {
        $registrationDate2 = $this->registrationDate;
        $currentDate = new \DateTime();
        $interval = $currentDate->diff($registrationDate2);

        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        $duration ="";

        if ($years > 0) {
            $duration .= $years . " year" . ($years > 1 ? "s" : "");
        }
    
        if ($months > 0) {
            if ($years > 0) {
                $duration .= ", ";
            }
            $duration .= $months . " month" . ($months > 1 ? "s" : "");
        }
    
        if ($days > 0) {
            if ($years > 0 || $months > 0) {
                $duration .= ", ";
            }
            $duration .= $days . " day" . ($days > 1 ? "s" : "");
        }
    
        return $duration;
    }

   

    public function getLastLoginDate(): ?\DateTimeInterface
    {
        return $this->lastLoginDate;
    }

    public function setLastLoginDate(?\DateTimeInterface $lastLoginDate): static
    {
        $this->lastLoginDate = $lastLoginDate;

        return $this;
    }

    public function getLastProfilEditDate(): ?\DateTimeInterface
    {
        return $this->lastProfilEditDate;
    }

    public function setLastProfilEditDate(?\DateTimeInterface $lastProfilEditDate): static
    {
        $this->lastProfilEditDate = $lastProfilEditDate;

        return $this;
    }



    public function __toString() {
        return $this->username;
    }
}
