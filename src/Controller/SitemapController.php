<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request, ProductRepository $productRepo): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls =[];

        // generate urls for principal pages
        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('preorder')];
        $urls[] = ['loc' => $this->generateUrl('bibliography')];
        $urls[] = ['loc' => $this->generateUrl('about')];
        $urls[] = ['loc' => $this->generateUrl('contact')];
        $urls[] = ['loc' => $this->generateUrl('cart')];
        $urls[] = ['loc' => $this->generateUrl('app_register')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('legal_pages_informations')];
        $urls[] = ['loc' => $this->generateUrl('legal_pages_terms')];
        $urls[] = ['loc' => $this->generateUrl('legal_pages_privacy')];

        // generate all urls for books pages
        foreach ($productRepo->findBy(['type' => 1]) as $book)
        {
            $urls[] = [
                'loc' => $this->generateUrl('book', ['id' => $book->getId()])
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/sitemap.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
        );

        $response->headers->set('Content-type', 'text/xml');

        return $response;
    }
}
