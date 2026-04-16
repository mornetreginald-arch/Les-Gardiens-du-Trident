<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    // #[Route('/main', name: 'app_main')]
    // public function index(): Response
    // {
    //     return $this->render('main/index.html.twig', [
    //         'controller_name' => 'MainController',
    //     ]);
    // }

    



    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/elevage', name: 'app_elevage')]
    public function elevage(): Response
    {
        return $this->render('main/elevage.html.twig');
    }

    #[Route('/geant', name: 'app_geant')]
    public function geant(): Response
    {
        return $this->render('main/geant.html.twig');
    }

    #[Route('/quotidient', name: 'app_quotidient')]
    public function quotidient(): Response
    {
        return $this->render('main/quotidient.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig');
    }

    #[Route('/chiots', name: 'app_chiots')]
    public function chiots(): Response
    {
        return $this->render('main/chiots.html.twig');
    }
}
