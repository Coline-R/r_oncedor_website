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
            
             // send user's message to the admin
            $email = (new TemplatedEmail())
            ->from('contact@dev-r-oncedor.fr')
            ->to(new Address('test@test.com'))
            ->subject('Message du formulaire de contact de dev-r-oncedor.fr')

            ->htmlTemplate('contact/contact_mail.html.twig')

            ->context([
                'formData' => $contactFormData
            ])
            ;

            $mailer->send($email);

            $this->addFlash(
                'info',
                'Votre message a bien été envoyé !'
            );

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
