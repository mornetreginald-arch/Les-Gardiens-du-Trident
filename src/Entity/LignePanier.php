<?php

namespace App\Entity;

use App\Repository\LignePanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LignePanierRepository::class)]
#[ORM\Table(
    uniqueConstraints: [
        new ORM\UniqueConstraint(name: "unique_chiot", columns: ["chiot_id"])
    ]
)]
class LignePanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lignePaniers')]
    private ?Articles $articles = null;

    #[ORM\ManyToOne]
    private ?Chiots $chiot = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'lignePaniers')]
    private ?Panier $panier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticles(): ?Articles
    {
        return $this->articles;
    }

    public function setArticles(?Articles $articles): static
    {
        $this->articles = $articles;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): static
    {
        $this->panier = $panier;

        return $this;
    }

    public function getChiot(): ?Chiots
    {
        return $this->chiot;
    }

    public function setChiot(?Chiots $chiot): self
    {
        $this->chiot = $chiot;
        return $this;
    }
}
