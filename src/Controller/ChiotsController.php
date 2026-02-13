<?php

namespace App\Controller;

use App\Entity\Chiots;
use App\Form\ChiotsType;
use App\Repository\ChiotsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chiots')]
final class ChiotsController extends AbstractController
{
    #[Route(name: 'app_chiots_index', methods: ['GET'])]
    public function index(ChiotsRepository $chiotsRepository): Response
    {
        return $this->render('chiots/index.html.twig', [
            'chiots' => $chiotsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chiots_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chiot = new Chiots();
        $form = $this->createForm(ChiotsType::class, $chiot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chiot);
            $entityManager->flush();

            return $this->redirectToRoute('app_chiots_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chiots/new.html.twig', [
            'chiot' => $chiot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chiots_show', methods: ['GET'])]
    public function show(Chiots $chiot): Response
    {
        return $this->render('chiots/show.html.twig', [
            'chiot' => $chiot,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chiots_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chiots $chiot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChiotsType::class, $chiot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chiots_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chiots/edit.html.twig', [
            'chiot' => $chiot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chiots_delete', methods: ['POST'])]
    public function delete(Request $request, Chiots $chiot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chiot->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chiot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chiots_index', [], Response::HTTP_SEE_OTHER);
    }
}
