<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
public function login(
    Request $request,
    AuthenticationUtils $authenticationUtils,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager
): Response {

    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    $registrationForm = $this->createForm(RegistrationFormType::class);
    $registrationForm->handleRequest($request);

    $registerError = false;

    if ($registrationForm->isSubmitted()) {
        $registerError = true;

        if ($registrationForm->isValid()) {
            $user = $registrationForm->getData();

            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $registrationForm->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }
    }

    return $this->render('security/login.html.twig', [
        'registrationForm' => $registrationForm->createView(),
        'last_username' => $lastUsername,
        'error' => $error,
        'registerError' => $registerError,
    ]);
}
}
