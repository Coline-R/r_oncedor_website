<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalPagesController extends AbstractController
{
    /**
     * @Route("/legal/informations", name="legal_pages_informations")
     */
    public function legalInformations(): Response
    {
        return $this->render('legal_pages/informations.html.twig');
    }

    /**
     * @Route("/legal/terms", name="legal_pages_terms")
     */
    public function termsOfService(): Response
    {
        return $this->render('legal_pages/terms.html.twig');
    }

        /**
     * @Route("/legal/privacy", name="legal_pages_privacy")
     */
    public function privacyPolicy(): Response
    {
        return $this->render('legal_pages/privacy.html.twig');
    }
}
