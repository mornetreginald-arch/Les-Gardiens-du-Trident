<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    // Clé primaire
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    // Nom du produit
    #[ORM\Column(length: 255)]
    private ?string $nom_produit = null;

    // Prix (decimal)
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    // Stock disponible
    #[ORM\Column]
    private ?int $stock = null;

    // Nom du fichier image
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     *  Relation ManyToMany avec Commande
     * Un article peut appartenir à plusieurs commandes
     * @var Collection<int, Commande>
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, inversedBy: 'articles')]
    private Collection $commande;

    /**
     * Relation ManyToMany avec Categorie
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, mappedBy: 'articles')]
    private Collection $categories;

    /**
     * Relation OneToMany avec LignePanier
     * @var Collection<int, LignePanier>
     */
    #[ORM\OneToMany(targetEntity: LignePanier::class, mappedBy: 'articles')]
    private Collection $lignePaniers;

    /**
     * Relation OneToMany avec LigneCommande
     * @var Collection<int, LigneCommande>
     */
    #[ORM\OneToMany(targetEntity: LigneCommande::class, mappedBy: 'articles')]
    private Collection $ligneCommandes;

    
    // Initialisation des collections
    public function __construct()
    {
        $this->commande = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->lignePaniers = new ArrayCollection();
        $this->ligneCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getIdProduit(): ?int
    // {
    //     return $this->id_produit;
    // }

    // public function setIdProduit(int $id_produit): static
    // {
    //     $this->id_produit = $id_produit;

    //     return $this;
    // }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;

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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

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
     * Retourne les commandes liées
     * @return Collection<int, Commande>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    // Ajoute une commande
    public function addCommande(Commande $commande): static
    {
        if (!$this->commande->contains($commande)) {
            $this->commande->add($commande);
        }

        return $this;
    }

    // Supprime une commande
    public function removeCommande(Commande $commande): static
    {
        $this->commande->removeElement($commande);

        return $this;
    }

    /**
     * Retourne les catégories
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    // Ajoute une catégorie
    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addArticle($this);
        }

        return $this;
    }

    // Supprime une catégorie
    public function removeCategory(Categorie $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeArticle($this);
        }

        return $this;
    }

    /**
     * Lignes du panier liées
     * @return Collection<int, LignePanier>
     */
    public function getLignePaniers(): Collection
    {
        return $this->lignePaniers;
    }

    public function addLignePanier(LignePanier $lignePanier): static
    {
        if (!$this->lignePaniers->contains($lignePanier)) {
            $this->lignePaniers->add($lignePanier);
            // Lien inverse
            $lignePanier->setArticles($this);
        }

        return $this;
    }

    public function removeLignePanier(LignePanier $lignePanier): static
    {
        if ($this->lignePaniers->removeElement($lignePanier)) {
            if ($lignePanier->getArticles() === $this) {
                $lignePanier->setArticles(null);
            }
        }
        return $this;
    }

    // Permet d'afficher le nom du produit automatiquement
    public function __toString(): string
{
    return $this->nom_produit ?? '';
}

    /**
     * Lignes de commande liées
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
            $ligneCommande->setArticles($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): static
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            if ($ligneCommande->getArticles() === $this) {
                $ligneCommande->setArticles(null);
            }
        }

        return $this;
    }

}
