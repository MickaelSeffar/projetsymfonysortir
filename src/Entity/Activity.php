<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Assert\GreaterThan("today", message="La date de la sortie doit être supérieur à la date du jour")
     * @Assert\GreaterThan(propertyPath="registrationDeadLine",message="La date sortie doit être après la date de limite d'inscritpion")
     */
    private $beginDateTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     *  @Assert\DateTime()
     * @Assert\GreaterThan("today", message="La date de limite d'inscription doit être supérieur à la date du jour")
     */
    private $registrationDeadline;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     */
    private $maximumUserNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     */
    private $currentUserNumber;

    /**
     * @ORM\Column(type="text", nullable=true)
     *  @Assert\NotBlank(message="Ce champs est obligatoire")
     */
    private $detail;

    /**
     * @ORM\ManyToOne(targetEntity=State::class)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, cascade={"persist"})
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     */
    private $campus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cancellationReason;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ActivityManager", cascade="persist")
     */
    private $manager;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\Register",mappedBy="activity")
     */
    private $registrations;

    /**
     * @ORM\Column(type="boolean",options={"default":true})
     */
    private $active;

    public function __construct()
    {
        $this->registrations=new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBeginDateTime(): ?\DateTimeInterface
    {
        return $this->beginDateTime;
    }

    public function setBeginDateTime(\DateTimeInterface $beginDateTime): self
    {
        $this->beginDateTime = $beginDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistrationDeadline(): ?\DateTimeInterface
    {
        return $this->registrationDeadline;
    }

    public function setRegistrationDeadline(\DateTimeInterface $registrationDeadline): self
    {
        $this->registrationDeadline = $registrationDeadline;

        return $this;
    }

    public function getMaximumUserNumber(): ?int
    {
        return $this->maximumUserNumber;
    }

    public function setMaximumUserNumber(int $maximumUserNumber): self
    {
        $this->maximumUserNumber = $maximumUserNumber;

        return $this;
    }

    public function getCurrentUserNumber(): ?int
    {
        return $this->currentUserNumber;
    }

    public function setCurrentUserNumber(?int $currentUserNumber): self
    {
        $this->currentUserNumber = $currentUserNumber;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getCancellationReason(): ?string
    {
        return $this->cancellationReason;
    }

    public function setCancellationReason(?string $cancellationReason): self
    {
        $this->cancellationReason = $cancellationReason;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    /**
     * @param ArrayCollection $registrations
     */
    public function setRegistrations(ArrayCollection $registrations): void
    {
        $this->registrations = $registrations;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Teste si un User est inscrit à cette sortie
     *
     * @param UserInterface $user
     * @return bool
     */
    public function isSubscribed(UserInterface $user): bool
    {
        foreach($this->getRegistrations() as $sub){
            if ($sub->getUser() === $user){
                return true;
            }
        }
        return false;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

}
