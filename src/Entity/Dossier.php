<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DossierRepository::class)]
class Dossier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $partiesImpliquees = null;

    
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDepot = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $documents = [];
   
    #[ORM\ManyToMany(targetEntity: Lawyer::class)]
    private Collection $lawyers;


    #[ORM\OneToMany(mappedBy: 'dossier', targetEntity: PreuvesDossier::class)]
    private Collection $preuvesDossiers;

    public function __construct()
    {
        $this->preuvesDossiers = new ArrayCollection();
        $this->lawyers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }
    public function getPartiesImpliquees(): ?string
    {
        return $this->partiesImpliquees;
    }

    public function setPartiesImpliquees(string $partiesImpliquees): static
    {
        $this->partiesImpliquees = $partiesImpliquees;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): static
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getDocuments(): ?array
    {
        return $this->documents;
    }

    public function setDocuments(?array $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    public function getLawyers(): Collection
    {
        return $this->lawyers;
    }

    public function addLawyer(Lawyer $lawyer): static
    {
        if (!$this->lawyers->contains($lawyer)) {
            $this->lawyers[] = $lawyer;
        }

        return $this;
    }

    public function removeLawyer(Lawyer $lawyer): static
    {
        $this->lawyers->removeElement($lawyer);

        return $this;
    }
    

    public function getPreuvesDossiers(): Collection
    {
        return $this->preuvesDossiers;
    }

    public function addPreuvesDossier(PreuvesDossier $preuvesDossier): self
    {
        if (!$this->preuvesDossiers->contains($preuvesDossier)) {
            $this->preuvesDossiers[] = $preuvesDossier;
            $preuvesDossier->setDossier($this);
        }

        return $this;
    }

    public function removePreuvesDossier(PreuvesDossier $preuvesDossier): self
    {
        if ($this->preuvesDossiers->removeElement($preuvesDossier)) {
            if ($preuvesDossier->getDossier() === $this) {
                $preuvesDossier->setDossier(null);
            }
        }

        return $this;
    }
}
