<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Chiots;
use App\Entity\Panier;
use App\Entity\LignePanier;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Repository\ChiotsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/articles')]
final class ArticlesController extends AbstractController
{
    #[Route(name: 'app_articles_index', methods: ['GET'])]
    public function index(
        ArticlesRepository $articlesRepository,
        ChiotsRepository $chiotsRepository
    ): Response {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
            'chiots'   => $chiotsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_articles_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

            if ($imageFile instanceof UploadedFile) {

                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // erreur
                }

                $article->setImage($newFilename);
            }

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_articles_index');
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form'    => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_articles_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Articles $article, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_articles_index');
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form'    => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Articles $article, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('app_articles_index');
    }

    /*
    =====================================================
    AJOUT ARTICLE AU PANIER
    =====================================================
    */
    #[Route('/article/ajouter-au-panier/{id}', name: 'app_cart_add')]
    public function ajouterAuPanier(int $id, ArticlesRepository $articlesRepo, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Veuillez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        $article = $articlesRepo->find($id);

        if ($article->getPrix() == 0) {
            $this->addFlash('danger', 'Cet article est inclus uniquement avec une réservation.');
            return $this->redirectToRoute('app_articles_index');
        }


        if (!$article) {
            throw $this->createNotFoundException("Article introuvable.");
        }

        // 🔥 Sécurité rupture stock
        if ($article->getStock() <= 0) {
            $this->addFlash('danger', 'Produit en rupture de stock.');
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
        $ligne->setArticles($article);
        $ligne->setQuantite(1);
        $ligne->setPanier($panier);

        $em->persist($ligne);
        $em->flush();

        $this->addFlash('success', 'Article ajouté au panier.');

        return $this->redirectToRoute('app_articles_index');
    }

    /*
    =====================================================
    AJOUT CHIOT + ARTICLE INCLUS AUTOMATIQUE
    =====================================================
    */
    #[Route('/cart/add/chiot/{id}', name: 'app_cart_add_chiot')]
    public function addChiot(Chiots $chiot, EntityManagerInterface $em, ArticlesRepository $articlesRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // 🔥 Vérifier si chiot déjà vendu
        if ($chiot->isEstVendu()) {
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

        /*
        ======================
        1️⃣ Ajouter le chiot
        ======================
        */
        $ligneChiot = new LignePanier();
        $ligneChiot->setChiot($chiot);
        $ligneChiot->setQuantite(1);
        $ligneChiot->setPanier($panier);
        $em->persist($ligneChiot);

        /*
        ======================
        2️⃣ Ajouter tous les articles inclus (prix = 0)
        ======================
        */

        $articlesInclus = $articlesRepository->findBy(['prix' => 0]);

        foreach ($articlesInclus as $articleInclus) {

            $ligneArticle = new LignePanier();
            $ligneArticle->setArticles($articleInclus);
            $ligneArticle->setQuantite(1);
            $ligneArticle->setPanier($panier);

            $em->persist($ligneArticle);
        }


        $em->flush();

        $this->addFlash('success', 'Chiot réservé avec article inclus.');

        return $this->redirectToRoute('app_panier');
    }

    /*
    =====================================================
    AJOUT UPLOAD IMAGE ARTICLES
    =====================================================
    */
}
