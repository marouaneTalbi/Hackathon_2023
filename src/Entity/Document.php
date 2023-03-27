<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?Hackathon $hackathonOwner = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?Group $groupOwner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHackathonOwner(): ?Hackathon
    {
        return $this->hackathonOwner;
    }

    public function setHackathonOwner(?Hackathon $hackathonOwner): self
    {
        $this->hackathonOwner = $hackathonOwner;

        return $this;
    }

    public function getGroupOwner(): ?Group
    {
        return $this->groupOwner;
    }

    public function setGroupOwner(?Group $groupOwner): self
    {
        $this->groupOwner = $groupOwner;

        return $this;
    }
}
