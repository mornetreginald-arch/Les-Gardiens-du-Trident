<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    #[Route(name: 'app_commande_index', methods: ['GET'])]
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


    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    
    public function show(Commande $commande): Response
{
    $user = $this->getUser();

    if (!$this->isGranted('ROLE_ADMIN') && $commande->getUser() !== $this->getUser()) {
    throw $this->createAccessDeniedException();
}


    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // 🔒 Sécurité : empêcher de voir la commande d’un autre
    if ($commande->getUser() !== $user) {
        throw $this->createAccessDeniedException();
    }

    return $this->render('commande/show.html.twig', [
        'commande' => $commande,
    ]);
}

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
