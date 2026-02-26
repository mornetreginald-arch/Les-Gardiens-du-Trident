<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {

        $data = $request->request->all('registration_form');

        // Sécurité minimale
        if (!$data) {
            return $this->redirectToRoute('app_articles_index');
        }

        $user = new User();

        $user->setEmail($data['email'] ?? null);
        $user->setNom($data['nom'] ?? null);
        $user->setPrenom($data['prenom'] ?? null);
        $user->setTelephone($data['telephone'] ?? null);
        $user->setRue($data['rue'] ?? null);
        $user->setCodePostal($data['code_postal'] ?? null);
        $user->setVille($data['ville'] ?? null);
        $user->setPays($data['pays'] ?? null);

        // Hash du mot de passe
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $data['plainPassword'] ?? ''
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_articles_index');
    }
}