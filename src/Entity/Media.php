<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    private ?Content $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeMedia(): ?string
    {
        return $this->type;
    }

    public function setTypeMedia(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMediaUrl(): ?string
    {
        return $this->url;
    }

    public function setMediaUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(?Content $content): self
    {
        $this->content = $content;

        return $this;
    }
}
