<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Entity\Panier;
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
public function remove(
    LignePanier $lignePanier,
    EntityManagerInterface $em
): Response
{
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Vérifier que la ligne appartient bien à l'utilisateur
    if ($lignePanier->getPanier()->getUser() !== $user) {
        throw $this->createAccessDeniedException();
    }

    $em->remove($lignePanier);
    $em->flush();

    $this->addFlash('success', 'Produit supprimé du panier.');

    return $this->redirectToRoute('app_panier');
}
}
