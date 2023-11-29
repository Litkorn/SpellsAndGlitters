<?php

namespace App\Controller\Main;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to('spells.and.glitters@lunidev.fr')
                ->subject($contact->get('object')->getData())
                ->htmlTemplate('Main/contact/email.html.twig')
                ->context([
                    'mail'      => $contact->get('email')->getData(),
                    'object'    => $contact->get('object')->getData(),
                    'message'   => $contact->get('message')->getData()
                ]);
            $mailer->send($email);

            $this->addFlash('success', 'Votre e-mail a bien été envoyé');
            return $this->redirectToRoute('app_contact');
        }


        return $this->render('Main/contact/index.html.twig', [
            'form'  => $form,
            'controller_name'  => 'contact'
        ]);
    }
}
