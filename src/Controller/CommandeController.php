<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    /*
    ============================
    👑 ADMIN → TOUTES COMMANDES
    ============================
    */
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
public function index(EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // 👑 ADMIN → voir toutes les commandes groupées par utilisateur
    if ($this->isGranted('ROLE_ADMIN')) {

        $users = $em->getRepository(\App\Entity\User::class)->findAll();

        return $this->render('commande/index.html.twig', [
            'users' => $users,
            'is_admin' => true,
        ]);
    }

    // 👤 UTILISATEUR → voir uniquement ses commandes
    $commandes = $em->getRepository(Commande::class)
        ->findBy(['user' => $user], ['id' => 'DESC']);

    return $this->render('commande/index.html.twig', [
        'commandes' => $commandes,
        'is_admin' => false,
    ]);
}


    /*
    ============================
    👤 UTILISATEUR → SES COMMANDES
    ============================
    */
    #[Route('/mes-commandes', name: 'app_mes_commandes')]
    public function mesCommandes(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $commandes = $em->getRepository(Commande::class)
            ->findBy(['user' => $user], ['id' => 'DESC']);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /*
    ============================
    🔍 DÉTAIL D’UNE COMMANDE
    ============================
    */
    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Admin peut tout voir
        if (!$this->isGranted('ROLE_ADMIN') && $commande->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /*
    ============================
    🗑 SUPPRESSION (ADMIN)
    ============================
    */
    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index');
    }
}
