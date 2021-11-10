<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BibliographyController extends AbstractController
{
    /**
     * @Route("/bibliography", name="bibliography")
     */
    public function index(): Response
    {

        $books = $this->getDoctrine()->getManager()->getRepository(Product::class)->findBy(array('type' => '2'));
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();

        return $this->render('bibliography/bibliography.html.twig', [
            'books' => $books,
            'product' => $product
        ]);
    }
}
