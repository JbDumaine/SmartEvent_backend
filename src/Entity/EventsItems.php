<?php

namespace App\Entity;

use App\Repository\EventsItemsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventsItemsRepository::class)
 */
class EventsItems
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("eventItem:read")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="item")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups("eventItem:read")
     */
    private Event $event;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class)
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups("eventItem:read")
     */
    private Item $item;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups("eventItem:read")
     */
    private bool $isChecked;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function setItem(Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getIsChecked(): bool
    {
        return $this->isChecked;
    }

    public function setIsChecked(bool $isChecked): self
    {
        $this->isChecked = $isChecked;

        return $this;
    }
}
