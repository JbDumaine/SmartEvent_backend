<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="event_title", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=255)
     */
    private string $title;

    /**
     * @ORM\Column(name="event_date", type="datetime")
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private DateTime $eventDate;

    /**
     * @ORM\Column(name="event_description", type="text")
     *
     * @Assert\NotBlank()
     */
    private string $description;

    /**
     * @ORM\Column(name="picture_path", type="string", length=255, nullable=true)
     *
     * @Assert\Length(min=2, max=255)
     */
    private ?string $picturePath;

    /**
     * @ORM\Column(name="event_address", type="text")
     *
     * @Assert\NotBlank()
     */
    private string $address;

    /***************************************************************************************
     * Relations avec les autres entités
     **************************************************************************************/

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="organizedEvents")
     *
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private User $organizer;

    /**
     * @ORM\ManyToOne(targetEntity="EventType")
     *
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private EventType $type;

    /**
     * @ORM\ManyToMany(targetEntity="Item")
     * @ORM\JoinTable(name="events_items",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")}
     *      )
     */
    private Collection $items;

    /**
     * Invitation de l'évènement
     *
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="event", orphanRemoval=true)
     */
    private Collection $invitations;

    public function __construct() {
        $this->items = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEventDate(): DateTime
    {
        return $this->eventDate;
    }

    public function setEventDate(DateTime $eventDate): self
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(?string $picturePath): self
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOrganizer(): User
    {
        return $this->organizer;
    }

    public function setOrganizer(User $organizer): void
    {
        $this->organizer = $organizer;
    }

    public function getType(): EventType
    {
        return $this->type;
    }

    public function setType(EventType $type): void
    {
        $this->type = $type;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
        }

    }

    public function removeItem(Item $item): void
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
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
            $invitation->setEvent($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->contains($invitation)) {
            $this->invitations->removeElement($invitation);
            // set the owning side to null (unless already changed)
            $invitation->delete();
        }

        return $this;
    }
}
