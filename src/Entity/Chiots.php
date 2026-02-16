<?php

namespace App\Entity;

use App\Repository\ChiotsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChiotsRepository::class)]
class Chiots
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column]
    // private ?int $id_chiot = null;

    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur_collier = null;

    #[ORM\ManyToOne(inversedBy: 'chiots')]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getIdChiot(): ?int
    // {
    //     return $this->id_chiot;
    // }

    // public function setIdChiot(int $id_chiot): static
    // {
    //     $this->id_chiot = $id_chiot;

    //     return $this;
    // }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getCouleurCollier(): ?string
    {
        return $this->couleur_collier;
    }

    public function setCouleurCollier(string $couleur_collier): static
    {
        $this->couleur_collier = $couleur_collier;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
