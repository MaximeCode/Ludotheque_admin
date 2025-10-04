<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, GameRoom>
     */
    #[ORM\OneToMany(targetEntity: GameRoom::class, mappedBy: 'owner')]
    private Collection $ownedRooms;

    public function __construct()
    {
        $this->ownedRooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, GameRoom>
     */
    public function getOwnedRooms(): Collection
    {
        return $this->ownedRooms;
    }

    public function addOwnedRoom(GameRoom $gameRoom): static
    {
        if (!$this->ownedRooms->contains($gameRoom)) {
            $this->ownedRooms->add($gameRoom);
            $gameRoom->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedRoom(GameRoom $gameRoom): static
    {
        if ($this->ownedRooms->removeElement($gameRoom)) {
            // set the owning side to null (unless already changed)
            if ($gameRoom->getOwner() === $this) {
                $gameRoom->setOwner(null);
            }
        }

        return $this;
    }
}
