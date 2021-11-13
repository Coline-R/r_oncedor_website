<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $contactFormData = $form->getData();

            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('contact@roncedor-test.fr')
                ->subject('Formulaire de contact - Site R. Oncedor')
                ->text('Emeteur : '.$contactFormData['name'].
                'Mail de l\'emetteur : '.$contactFormData['email'].$contactFormData['message'],'text/plain');
            
            $mailer->send($message);

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
