<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/book/{id}", name="book")
     */
    public function index(Product $product): Response
    {
        return $this->render('book/book.html.twig', [
            'book' => $product
        ]);
    }
}
