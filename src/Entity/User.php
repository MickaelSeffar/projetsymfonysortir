<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Type(type="string", message="Le pseudo ne doit contenir que du texte")
     * @Assert\Length(min="5", minMessage="Le pseudo est trop court. Min 5 caractères")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre nom ne doit pas contenir de chiffre"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre prénom ne doit pas contenir de chiffre"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Regex(pattern="#^0[1-68]([-. ]?[0-9]{2}){4}$#", message="Merci d'entrer 10 chiffres")
      */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrator;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="manager")
     */
    private $ActivityManager;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     */
    private $campus;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     strict=false,
     *     message="Merci d'entrer un email correct"
     * )
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Register", mappedBy="user")
     */
    private $registrations;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $csvFieldAdmin;

    public function __construct()
    {
        $this->ActivityManager = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdministrator(): ?bool
    {
        return $this->administrator;
    }

    public function setAdministrator(bool $administrator): self
    {
        $this->administrator = $administrator;

        return $this;
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

    /**
     * @return Collection|Activity[]
     */
    public function getActivityManager(): Collection
    {
        return $this->ActivityManager;
    }

    public function addActivityManager(Activity $activityManager): self
    {
        if (!$this->ActivityManager->contains($activityManager)) {
            $this->ActivityManager[] = $activityManager;
            $activityManager->setManager($this);
        }

        return $this;
    }

    public function removeActivityManager(Activity $activityManager): self
    {
        if ($this->ActivityManager->removeElement($activityManager)) {
            // set the owning side to null (unless already changed)
            if ($activityManager->getManager() === $this) {
                $activityManager->setManager(null);
            }
        }

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }

    /**
     * @param mixed $registrations
     */
    public function setRegistrations($registrations): void
    {
        $this->registrations = $registrations;
    }

    public function __toString()
    {
       return $this->username;
    }


    /**
     * @return mixed
     */
    public function getCsvFieldAdmin()
    {
        return $this->csvFieldAdmin;
    }

    /**
     * @param mixed $csvFieldAdmin
     */
    public function setCsvFieldAdmin($csvFieldAdmin): void
    {
        $this->csvFieldAdmin = $csvFieldAdmin;
    }


}
