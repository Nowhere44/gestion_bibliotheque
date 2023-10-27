<?php

namespace App\Entity;

use App\Repository\BibliothequeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BibliothequeRepository::class)]
class Bibliotheque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'bibliotheque', targetEntity: Livre::class)]
    private Collection $livres;

    
    
    #[ORM\OneToMany(mappedBy: 'bibliotheque', targetEntity: User::class)]
    private Collection $bibliothecaires;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
        $this->bibliothecaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): static
    {
        if (!$this->livres->contains($livre)) {
            $this->livres->add($livre);
            $livre->setBibliotheque($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        if ($this->livres->removeElement($livre)) {
            if ($livre->getBibliotheque() === $this) {
                $livre->setBibliotheque(null);
            }
        }

        return $this;
    }

    public function __toString(): string
{
    return $this->name;
}
   /**
     * @return Collection<int, User>
     */
    public function getBibliothecaires(): Collection
    {
        return $this->bibliothecaires;
    }

    public function addBibliothecaire(User $bibliothecaire): self
    {
        if (!$this->bibliothecaires->contains($bibliothecaire)) {
            $this->bibliothecaires[] = $bibliothecaire;
            $bibliothecaire->setBibliotheque($this);
        }

        return $this;
    }

    public function removeBibliothecaire(User $bibliothecaire): self
    {
        if ($this->bibliothecaires->removeElement($bibliothecaire)) {
            if ($bibliothecaire->getBibliotheque() === $this) {
                $bibliothecaire->setBibliotheque(null);
            }
        }

        return $this;
    }
}
