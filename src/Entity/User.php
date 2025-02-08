<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: 'App\Repository\UserRepository')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $oauthId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $oauthType = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getOAuthId(): ?string
    {
        return $this->oauthId;
    }

    public function getOAuthType(): ?string
    {
        return $this->oauthType;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        // not needed for apps that do not check user passwords
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): null
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->oauthId,
            $this->oauthType,
            $this->roles,
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        [$this->id, $this->email, $this->oauthId, $this->oauthType, $this->roles] = unserialize($serialized);
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    // Setters
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setOAuthId(?string $oauthId): self
    {
        $this->oauthId = $oauthId;

        return $this;
    }

    public function setOAuthType(?string $oauthType): self
    {
        $this->oauthType = $oauthType;

        return $this;
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
