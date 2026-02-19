<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LignePanier;
use App\Entity\Panier;
use App\Entity\LigneCommande;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(PanierRepository $panierRepository,EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $panier = $panierRepository->findOneBy([
        'user' => $user
    ]);

    if (!$panier) {
        $panier = new Panier();
        $panier->setUser($user);
        $em->persist($panier);
        $em->flush();
    }

    return $this->render('panier/index.html.twig', [
        'panier' => $panier,
    ]);
}

#[Route('/panier/supprimer/{id}', name: 'app_panier_remove')]
public function remove(LignePanier $lignePanier,EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Vérifier que la ligne appartient bien à l'utilisateur
    if ($lignePanier->getPanier()->getUser() !== $user) {
        throw $this->createAccessDeniedException();
    }

    $panier = $lignePanier->getPanier();


    if ($lignePanier->getChiot()) {

        // Supprimer tous les articles inclus (prix = 0)
        foreach ($panier->getLignePaniers() as $ligne) {

            if ($ligne->getArticles() && $ligne->getArticles()->getPrix() == 0) {
                $em->remove($ligne);
            }
        }
    }

    $em->remove($lignePanier);
    $em->flush();

    $this->addFlash('success', 'Produit supprimé du panier.');

    return $this->redirectToRoute('app_panier');
}

#[Route('/panier/valider', name: 'app_panier_valider')]
public function valider(EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $panier = $em->getRepository(Panier::class)
        ->findOneBy(['user' => $user]);

    if (!$panier || $panier->getLignePaniers()->isEmpty()) {
        $this->addFlash('danger', 'Votre panier est vide.');
        return $this->redirectToRoute('app_panier');
    }

    $commande = new Commande();
    $commande->setUser($user);
    $commande->setDateCommande(new \DateTime());

    $em->persist($commande);

    $total = 0;

    foreach ($panier->getLignePaniers() as $lignePanier) {

        $ligneCommande = new LigneCommande();
        $ligneCommande->setCommande($commande);
        $ligneCommande->setQuantite($lignePanier->getQuantite());

        // 🔵 ARTICLE
        if ($lignePanier->getArticles()) {

            $article = $lignePanier->getArticles();

            // Vérifier le stock
            if ($article->getStock() < $lignePanier->getQuantite()) {
                $this->addFlash('danger', 'Stock insuffisant pour ' . $article->getNomProduit());
                return $this->redirectToRoute('app_panier');
            }

            // Réduire le stock
            $article->setStock(
                $article->getStock() - $lignePanier->getQuantite()
            );

            $ligneCommande->setArticles($article);
            $ligneCommande->setPrix($article->getPrix());

            $total += $article->getPrix() * $lignePanier->getQuantite();
        }

        // 🐶 CHIOT
        if ($lignePanier->getChiot()) {

            $chiot = $lignePanier->getChiot();

            if ($chiot->isEstVendu()) {
                $this->addFlash('danger', 'Ce chiot est déjà vendu.');
                return $this->redirectToRoute('app_panier');
            }

            $chiot->setEstVendu(true);
            $em->persist($chiot);

            $ligneCommande->setChiot($chiot);
            $ligneCommande->setPrix($chiot->getPrix());

            $total += $chiot->getPrix();
        }

        $em->persist($ligneCommande);
        $em->remove($lignePanier);
    }

    $commande->setTotal($total);

    $em->flush();

    $this->addFlash('success', 'Commande validée avec succès !');

    return $this->redirectToRoute('app_articles_index');
}

}



