<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginDateTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDeadline;

    /**
     * @ORM\Column(type="integer")
     */
    private $maximumUserNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentUserNumber;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detail;

    /**
     * @ORM\ManyToOne(targetEntity=State::class)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     */
    private $campus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cancellationReason;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ActivityManager")
     */
    private $manager;

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
}