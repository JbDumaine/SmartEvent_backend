<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *    uniqueConstraints={
 *      @ORM\UniqueConstraint(name="invitation_invitation_token", columns={"invitation_token"})
 *   }
 * )
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
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=255)
     *
     * @Groups({"invitation:read"})
     */
    private string $email;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="invitations")
     *
     * @Assert\Valid
     *
     * @Groups({"invitation:read"})
     */
    private Event $event;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitations")
     *
     * @Assert\Valid
     */
    private ?User $guest;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class)
     *
     * @Assert\Valid
     * @Groups("invitation:read")
     */
    private Status $status;

    /**
     * @ORM\Column(name="invitation_token", type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=255)
     */
    private string $invitationToken;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
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

    public function getGuest(): ?UserInterface
    {
        return $this->guest;
    }

    public function setGuest(?UserInterface $guest): self
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
