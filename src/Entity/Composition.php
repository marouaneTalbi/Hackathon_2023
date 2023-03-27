<?php

namespace App\Entity;

use App\Repository\CompositionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompositionRepository::class)]
class Composition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbDev = null;

    #[ORM\Column]
    private ?int $nbMarketing = null;

    #[ORM\Column]
    private ?int $nbDesign = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbDev(): ?int
    {
        return $this->nbDev;
    }

    public function setNbDev(int $nbDev): self
    {
        $this->nbDev = $nbDev;

        return $this;
    }

    public function getNbMarketing(): ?int
    {
        return $this->nbMarketing;
    }

    public function setNbMarketing(int $nbMarketing): self
    {
        $this->nbMarketing = $nbMarketing;

        return $this;
    }

    public function getNbDesign(): ?int
    {
        return $this->nbDesign;
    }

    public function setNbDesign(int $nbDesign): self
    {
        $this->nbDesign = $nbDesign;

        return $this;
    }
}
