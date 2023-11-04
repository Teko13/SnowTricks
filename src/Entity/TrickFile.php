<?php

namespace App\Entity;

use App\Repository\TrickFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickFileRepository::class)]
class TrickFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type_file = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[ORM\JoinColumn(name: "trick_id", referencedColumnName: "id", nullable: false)]
    private ?Trick $trick_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeFile(): ?string
    {
        return $this->type_file;
    }

    public function setTypeFile(string $type_file): static
    {
        $this->type_file = $type_file;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getTrickId(): ?Trick
    {
        return $this->trick_id;
    }

    public function setTrickId(?Trick $trick_id): static
    {
        $this->trick_id = $trick_id;

        return $this;
    }
}
