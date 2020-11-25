<?php

namespace App\Entity;

use App\Repository\AuthAccessTokensRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(
 *    uniqueConstraints={
 *      @ORM\UniqueConstraint(name="auth_access_tokens_unique", columns={"id"})
 *   }
 * )
 * @ORM\Entity(repositoryClass=AuthAccessTokensRepository::class)
 */
class AuthAccessTokens
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private string $id;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private int $userId;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;


    public function __construct(string $id, int $userId) {
        $this->id = $id;
        $this->userId = $userId;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
