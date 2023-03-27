<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\YearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YearRepository::class)]
class Year
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $date = null;

    #[ORM\OneToMany(mappedBy: 'year', targetEntity: Hackathon::class)]
    private Collection $hackathons;

    public function __construct()
    {
        $this->hackathons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Hackathon>
     */
    public function getHackathons(): Collection
    {
        return $this->hackathons;
    }

    public function addHackathon(Hackathon $hackathon): self
    {
        if (!$this->hackathons->contains($hackathon)) {
            $this->hackathons->add($hackathon);
            $hackathon->setYear($this);
        }

        return $this;
    }

    public function removeHackathon(Hackathon $hackathon): self
    {
        if ($this->hackathons->removeElement($hackathon)) {
            // set the owning side to null (unless already changed)
            if ($hackathon->getYear() === $this) {
                $hackathon->setYear(null);
            }
        }

        return $this;
    }
}
