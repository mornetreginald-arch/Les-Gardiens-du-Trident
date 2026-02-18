<?php

namespace App\Entity;

use App\Repository\ChiotsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    /**
     * @var Collection<int, LigneCommande>
     */
    #[ORM\OneToMany(targetEntity: LigneCommande::class, mappedBy: 'chiot')]
    private Collection $ligneCommandes;

    #[ORM\Column]
    private ?bool $estVendu = null;

    public function __construct()
    {
        $this->ligneCommandes = new ArrayCollection();
    }

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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, LigneCommande>
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): static
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes->add($ligneCommande);
            $ligneCommande->setChiot($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): static
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getChiot() === $this) {
                $ligneCommande->setChiot(null);
            }
        }

        return $this;
    }

    public function isEstVendu(): ?bool
    {
        return $this->estVendu;
    }

    public function setEstVendu(bool $estVendu): static
    {
        $this->estVendu = $estVendu;

        return $this;
    }
}
