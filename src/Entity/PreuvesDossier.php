<?php

namespace App\Entity;

use App\Repository\PreuvesDossierRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PreuvesDossierRepository::class)]
class PreuvesDossier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;



    #[ORM\Column(type: "text")]
    private ?string $preuves = null;

    #[ORM\ManyToOne(targetEntity: Dossier::class, inversedBy: "preuvesDossiers")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dossier $dossier = null;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getPreuves(): ?string
    {
        return $this->preuves;
    }

    public function setPreuves(string $description): self
    {
        $this->preuves = $description;

        return $this;
    }

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

}
