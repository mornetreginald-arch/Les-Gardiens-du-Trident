<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Chiots;
use App\Form\ArticlesType;
use App\Entity\Panier;
use App\Entity\LignePanier;
use App\Repository\ArticlesRepository;
use App\Repository\ChiotsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/articles')]
final class ArticlesController extends AbstractController
{
    #[Route(name: 'app_articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository, ChiotsRepository $chiotsRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
            'chiots' => $chiotsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_articles_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_articles_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/article/ajouter-au-panier/{id}', name: 'app_cart_add')]
    public function ajouterAuPanier(int $id, ArticlesRepository $articlesRepo, EntityManagerInterface $em): Response
    {
        // 1. Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('danger', 'Veuillez vous connecter pour remplir votre panier.');
            return $this->redirectToRoute('app_login');
        }

        // 2. Trouver l'article
        $article = $articlesRepo->find($id);
        if (!$article) {
            throw $this->createNotFoundException("Cet article n'existe pas.");
        }

        // 3. Récupérer ou créer le Panier de l'utilisateur
        $panier = $em->getRepository(Panier::class)->findOneBy([
    'user' => $user
]);
        if (!$panier) {
            $panier = new Panier();
            $panier->setUser($user);
            $em->persist($panier);
        }

        // 4. Gérer la ligne de panier (vérifier si l'article y est déjà)
        $ligneExistante = null;
        foreach ($panier->getLignePaniers() as $ligne) {
            if ($ligne->getArticles() === $article) {
                $ligneExistante = $ligne;
                break;
            }
        }

        if ($ligneExistante) {
            $ligneExistante->setQuantite($ligneExistante->getQuantite() + 1);
        } else {
            $nouvelleLigne = new LignePanier();
            $nouvelleLigne->setArticles($article);
            $nouvelleLigne->setQuantite(1);
            $nouvelleLigne->setPanier($panier);
            $em->persist($nouvelleLigne);
        }

        // 5. Sauvegarder
        $em->flush();

        $this->addFlash('success', 'L\'article a été ajouté à votre panier !');
        
        // Rediriger vers la page des articles
        return $this->redirectToRoute('app_articles_index');
    }

    #[Route('/cart/add/chiot/{id}', name: 'app_cart_add_chiot')]
public function addChiot(Chiots $chiot, EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // 🔥 Vérifier si déjà réservé
    $ligneExistante = $em->getRepository(LignePanier::class)
        ->findOneBy(['chiot' => $chiot]);

    if ($ligneExistante) {
        $this->addFlash('danger', 'Ce chiot est déjà réservé.');
        return $this->redirectToRoute('app_articles_index');
    }

    $panier = $em->getRepository(Panier::class)
        ->findOneBy(['user' => $user]);

    if (!$panier) {
        $panier = new Panier();
        $panier->setUser($user);
        $em->persist($panier);
    }

    $ligne = new LignePanier();
    $ligne->setChiot($chiot);
    $ligne->setQuantite(1);
    $ligne->setPanier($panier);

    $em->persist($ligne);
    $em->flush();

    $this->addFlash('success', 'Chiot réservé avec succès !');

    return $this->redirectToRoute('app_articles_index');
}

}
