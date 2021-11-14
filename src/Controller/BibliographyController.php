<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BibliographyController extends AbstractController
{
    /**
     * @Route("/bibliography", name="bibliography")
     */
    public function index(ProductRepository $productRepo): Response
    {

        $books = $productRepo->findBy(array('type' => '2'));
        $product = $productRepo->findAll();

        return $this->render('bibliography/bibliography.html.twig', [
            'books' => $books,
            'product' => $product
        ]);
    }
}
