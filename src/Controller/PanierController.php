<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LignePanier;
use App\Entity\Panier;
use App\Entity\LigneCommande;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
public function valider(EntityManagerInterface $em, MailerInterface $mailer): Response
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

    /*
    ======================
    ENVOI EMAIL ADMIN
    ======================
    */

    $details = "";

    foreach ($commande->getLigneCommandes() as $ligne) {

        if ($ligne->getArticles()) {
            $details .= "
    <tr>
        <td>Article</td>
        <td>{$article->getNomProduit()}</td>
        <td>{$lignePanier->getQuantite()}</td>
        <td>{$article->getPrix()} €</td>
    </tr>
";
        }

        if ($ligne->getChiot()) {
            $details .= "
    <tr>
        <td>Chiot</td>
        <td>{$chiot->getSexe()} - Collier {$chiot->getCouleurCollier()}</td>
        <td>1</td>
        <td>{$chiot->getPrix()} €</td>
    </tr>
";
        }

        $details .= " | Quantité : " . $ligne->getQuantite();
        $details .= " | Prix : " . $ligne->getPrix() . " € <br>";
    }

    $clientNom = $user->getPrenom() . " " . $user->getNom();
$clientEmail = $user->getEmail();
$clientTelephone = $user->getTelephone();
$clientAdresse = $user->getRue();
$clientVille = $user->getCodePostal() . " " . $user->getVille();
$clientPays = $user->getPays();


    $email = (new Email())
    ->from('noreply@gardiens-trident.local')
    ->to('admin@admin.fr')
    ->subject('Nouvelle commande #' . $commande->getId())
    ->html("
        <h1>Nouvelle commande reçue</h1>

        <h2>Informations client</h2>
        <p><strong>Nom :</strong> {$clientNom}</p>
        <p><strong>Email :</strong> {$clientEmail}</p>
        <p><strong>Téléphone :</strong> {$clientTelephone}</p>
        <p><strong>Adresse :</strong> {$clientAdresse}</p>
        <p><strong>Ville :</strong> {$clientVille}</p>
        <p><strong>Pays :</strong> {$clientPays}</p>

        <hr>

        <h2>Détail de la commande</h2>

        <table border='1' cellpadding='5' cellspacing='0'>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                {$details}
            </tbody>
        </table>

        <h3>Total : {$total} €</h3>
    ");

$mailer->send($email);




    $mailer->send($email);


    $this->addFlash('success', 'Commande validée avec succès !');

    return $this->redirectToRoute('app_articles_index');
}


}



