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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?content $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type_media = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $media_url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?content
    {
        return $this->content;
    }

    public function setContent(?content $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTypeMedia(): ?string
    {
        return $this->type_media;
    }

    public function setTypeMedia(?string $type_media): self
    {
        $this->type_media = $type_media;

        return $this;
    }

    public function getMediaUrl(): ?string
    {
        return $this->media_url;
    }

    public function setMediaUrl(?string $media_url): self
    {
        $this->media_url = $media_url;

        return $this;
    }
}
