<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InvitationRepository::class)
 */
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="invitations")
     *
     * @Assert\Valid()
     */
    private Event $event;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitations")
     *
     * @Assert\Valid()
     */
    private User $guest;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class)
     *
     * @Assert\Valid()
     */
    private Status $status;

    /**
     * @ORM\Column(name="invitation_token", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=255)
     */
    private string $invitationToken;

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

    public function getGuest(): User
    {
        return $this->guest;
    }

    public function setGuest(User $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getInvitationToken(): string
    {
        return $this->invitationToken;
    }

    public function setInvitationToken(string $invitationToken): self
    {
        $this->invitationToken = $invitationToken;

        return $this;
    }
}
