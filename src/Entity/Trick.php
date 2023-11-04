<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(name: "author", referencedColumnName: "id", nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(name: "groupe_id", referencedColumnName: "id", nullable: false)]
    private ?Groupe $groupe_id = null;

    #[ORM\OneToMany(mappedBy: 'trick_id', targetEntity: TrickFile::class, orphanRemoval: true)]
    private Collection $files;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getGroupeId(): ?Groupe
    {
        return $this->groupe_id;
    }

    public function setGroupeId(?Groupe $groupe_id): static
    {
        $this->groupe_id = $groupe_id;

        return $this;
    }

    /**
     * @return Collection<int, TrickFile>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(TrickFile $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setTrickId($this);
        }

        return $this;
    }

    public function removeFile(TrickFile $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getTrickId() === $this) {
                $file->setTrickId(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
