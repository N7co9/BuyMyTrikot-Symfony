<?php

namespace App\Entity;

use App\Components\UserSettings\Persistence\TemporaryMailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemporaryMailsRepository::class)]
class TemporaryMails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $owned_by = null;

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

    public function getOwnedBy(): ?int
    {
        return $this->owned_by;
    }

    public function setOwnedBy(int $owned_by): static
    {
        $this->owned_by = $owned_by;

        return $this;
    }
}
