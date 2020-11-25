<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"event:read", "invitation:read"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=180)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=180)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=180)
     */
    private string $password;

    /**
    * @ORM\Column(type="string", unique=true, nullable=true)
    */
    private string $apiToken;

    /**
     * @ORM\Column(name="first_name", type="string", length=50)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     *
     * @Groups({"event:read", "invitation:read"})
     */
    private string $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=100)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     *
     * @Groups({"event:read", "invitation:read"})
     */
    private string $lastName;

    /**
     * @ORM\Column(name="phone_number", type="string", length=15)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=15)
     *
     * @Groups({"event:read", "invitation:read"})
     */
    private string $phoneNumber;

    /**
     * @ORM\Column(name="avatar_path", type="string", length=255, nullable=true)
     *
     * @Assert\Length(min=2, max=255)
     */
    private ?string $avatarPath;

    /***************************************************************************************
     * Relations avec les autres entités
     **************************************************************************************/

    /**
     * Evènement organisé par l'utilisateur.
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="organizer", cascade={"remove"}, orphanRemoval=true)
     */
    private Collection $organizedEvents;

    /**
     * Invitation reçu par l'utilisateur
     *
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="guest", cascade={"remove"}, orphanRemoval=true)
     */
    private Collection $invitations;

    /**
     * Contacts de l'utilisateur qui sont aussi des utilisateurs.
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="contacts",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")}
     *      )
     *
     * @Assert\Valid()
     */
    private Collection $contacts;

    public function __construct()
    {
        $this->organizedEvents = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
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
    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }

    public function setAvatarPath(?string $avatarPath): void
    {
        $this->avatarPath = $avatarPath;
    }

    public function getOrganizedEvents(): Collection
    {
        return $this->organizedEvents;
    }

    public function addOrganizedEvent(Event $event): void
    {
        if (!$this->organizedEvents->contains($event)) {
            $this->organizedEvents[] = $event;
            $event->setOrganizer($this);
        }
    }

    public function removeOrganizedEvent(Event $event): void
    {
        if ($this->organizedEvents->contains($event)) {
            $this->organizedEvents->removeElement($event);
        }

    }

    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations[] = $invitation;
            $invitation->setGuest($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->contains($invitation)) {
            $this->invitations->removeElement($invitation);
        }

        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(User $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }

    public function removeContact(User $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
