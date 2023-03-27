<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\HackathonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Gedmo\Mapping\Annotation\SortablePosition;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

#[ORM\Entity(repositoryClass: HackathonRepository::class)]
#[UniqueEntity(fields: ['name'])]
class Hackathon
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(options: ['default' => 0])]
    #[SortablePosition]
    private ?int $position = null;

    #[ORM\Column(length: 50)]
    #[NotBlank(message: 'Ce champs ne peut pas être vide.')]
    #[Type('string', message: 'Type erreur')]
    #[Length(
        min: 10,
        max: 50,
        minMessage: 'Il faut minimum 10 caractères.',
        maxMessage: 'Il faut maximum 50 caractères.'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[GreaterThan('+2 days')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'hackathons')]
    private ?Year $year = null;

    #[ORM\ManyToMany(targetEntity: School::class, inversedBy: 'hackathons')]
    private Collection $schools;

    #[ORM\OneToMany(mappedBy: 'hackathon', targetEntity: Group::class)]
    private Collection $groups;

    #[ORM\OneToMany(mappedBy: 'hackathonOwner', targetEntity: Document::class)]
    private Collection $documents;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'hackathons')]
    private Collection $participants;

    public function __construct()
    {
        $this->schools = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     * @return Hackathon
     */
    public function setPosition(?int $position): Hackathon
    {
        $this->position = $position;
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

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     * @return Hackathon
     */
    public function setSlug(?string $slug): Hackathon
    {
        $this->slug = $slug;
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getYear(): ?Year
    {
        return $this->year;
    }

    public function setYear(?Year $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, School>
     */
    public function getSchools(): Collection
    {
        return $this->schools;
    }

    public function addSchool(School $school): self
    {
        if (!$this->schools->contains($school)) {
            $this->schools->add($school);
        }

        return $this;
    }

    public function removeSchool(School $school): self
    {
        $this->schools->removeElement($school);

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->setHackathon($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getHackathon() === $this) {
                $group->setHackathon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setHackathonOwner($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getHackathonOwner() === $this) {
                $document->setHackathonOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
