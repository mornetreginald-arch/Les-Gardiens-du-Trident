<?php
namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Attribute\Route as AttributeRoute;

class ContactController extends AbstractController
{
    #[AttributeRoute('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from($data['email'])
                ->to('admin@admin.fr')
                ->subject('Nouveau message depuis le site')
                ->text(
                    "Nom : ".$data['nom']."\n".
                    "Email : ".$data['email']."\n".
                    "Téléphone : ".$data['telephone']."\n\n".
                    "Message : \n".$data['message']
                );

            $mailer->send($email);

            // Traitement des données (BDD, email, etc.)

            $this->addFlash('success', 'Message envoyé !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}