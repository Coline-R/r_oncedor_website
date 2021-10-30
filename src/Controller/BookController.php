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
    public function index($id): Response
    {
        $book = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($id);

        dump($book);

        return $this->render('book/index.html.twig', [
            'book' => $book
        ]);
    }
}
