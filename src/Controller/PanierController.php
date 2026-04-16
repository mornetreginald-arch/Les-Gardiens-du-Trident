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
use Symfony\Component\HttpFoundation\Request;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(PanierRepository $panierRepository, EntityManagerInterface $em): Response
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

    #[Route('/panier/supprimer/{id}', name: 'app_panier_remove', methods: ['POST'])]
    public function remove(Request $request, LignePanier $lignePanier, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifier que la ligne appartient bien à l'utilisateur
        if ($lignePanier->getPanier()->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        // --- DEBUT DE LA PROTECTION CSRF ---
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $lignePanier->getId(), $submittedToken)) {

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
        } else {
            // Si le token est invalide (tentative de piratage)
            $this->addFlash('danger', 'Action non autorisée (Token CSRF invalide).');
        }
        // --- FIN DE LA PROTECTION CSRF ---

        // On redirige l'utilisateur d'où il vient, ou vers l'index des articles par défaut
        return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('app_articles_index'));
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
        $details = ""; // ✅ IMPORTANT

        foreach ($panier->getLignePaniers() as $lignePanier) {

            $ligneCommande = new LigneCommande();
            $ligneCommande->setCommande($commande);
            $ligneCommande->setQuantite($lignePanier->getQuantite());

            //  ARTICLE
            //  ARTICLE
if ($lignePanier->getArticles()) {

    $article = $lignePanier->getArticles();

    //  Si article PAYANT → gérer stock
    if ((float)$article->getPrix() > 0) {

        if ($article->getStock() < $lignePanier->getQuantite()) {
            $this->addFlash('danger', 'Stock insuffisant pour ' . $article->getNomProduit());
            return $this->redirectToRoute('app_panier');
        }

        // Réduction stock UNIQUEMENT article payant
        $article->setStock(
            $article->getStock() - $lignePanier->getQuantite()
        );

        $total += $article->getPrix() * $lignePanier->getQuantite();
    }

    // Toujours ajouter à la commande
    $ligneCommande->setArticles($article);
    $ligneCommande->setPrix($article->getPrix());

    // Email détail
    $details .= "
    <tr>
        <td>Article</td>
        <td>{$article->getNomProduit()}</td>
        <td>{$lignePanier->getQuantite()}</td>
        <td>{$article->getPrix()} €</td>
    </tr>";
}


            //  CHIOT
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

                $details .= "
            <tr>
                <td>Chiot</td>
                <td>{$chiot->getSexe()} - Collier {$chiot->getCouleurCollier()}</td>
                <td>1</td>
                <td>{$chiot->getPrix()} €</td>
            </tr>";
            }

            $em->persist($ligneCommande);
            $em->remove($lignePanier);
        }

        $commande->setTotal($total);

        $em->flush();


        // ======================
        // EMAIL ADMIN
        // ======================

        $clientNom = $user->getPrenom() . " " . $user->getNom();
        $clientEmail = $user->getEmail();
        $clientTelephone = $user->getTelephone();
        $clientAdresse = $user->getRue();
        $clientVille = $user->getCodePostal() . " " . $user->getVille();
        $clientPays = $user->getPays();

        $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/logo-chien.png';

        $email = (new Email())
            ->from('noreply@gardiens-trident.local')
            ->to('admin@admin.fr')
            ->subject('Nouvelle commande #' . $commande->getId())
            ->embedFromPath($logoPath, 'logo_cid')
            ->html("
            <h1>Nouvelle commande reçue</h1>

            <div style='text-align:center;'>
            <img src='cid:logo_cid' alt='Logo' style='width:150px; margin-bottom:20px;'>
            </div>
            
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


        $this->addFlash('success', 'Commande validée avec succès !');

        return $this->redirectToRoute('app_articles_index');
    }
}
