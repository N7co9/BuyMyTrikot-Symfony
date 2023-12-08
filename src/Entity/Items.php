<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Model\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ApiResource]
class Items
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $clubWebsite = null;

    #[ORM\Column(length: 255)]
    private ?string $clubName = null;

    #[ORM\Column(length: 255)]
    private ?string $clubEmblem = null;

    #[ORM\Column(length: 255)]
    private ?string $clubFounded = null;

    #[ORM\Column(length: 255)]
    private ?string $clubAddress = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $nationality = null;

    #[ORM\Column(length: 255)]
    private ?string $position = null;

    #[ORM\Column(length: 255)]
    private ?string $thumbnail = null;
    #[ORM\Column]
    private ?int $team_id = null;
    #[ORM\Column]
    private ?int $item_id = null;

    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    public function setItemId(?int $item_id): static
    {
        $this->item_id = $item_id;
        return $this;
    }

    public function getTeamId(): ?string
    {
        return $this->team_id;
    }

    public function setTeamId(?string $team_id): static
    {
        $this->team_id = $team_id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClubWebsite(): ?string
    {
        return $this->clubWebsite;
    }

    public function setClubWebsite(string $clubWebsite): static
    {
        $this->clubWebsite = $clubWebsite;

        return $this;
    }

    public function getClubName(): ?string
    {
        return $this->clubName;
    }

    public function setClubName(string $clubName): static
    {
        $this->clubName = $clubName;

        return $this;
    }

    public function getClubEmblem(): ?string
    {
        return $this->clubEmblem;
    }

    public function setClubEmblem(string $clubEmblem): static
    {
        $this->clubEmblem = $clubEmblem;

        return $this;
    }

    public function getClubFounded(): ?string
    {
        return $this->clubFounded;
    }

    public function setClubFounded(string $clubFounded): static
    {
        $this->clubFounded = $clubFounded;

        return $this;
    }

    public function getClubAddress(): ?string
    {
        return $this->clubAddress;
    }

    public function setClubAddress(string $clubAddress): static
    {
        $this->clubAddress = $clubAddress;

        return $this;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
