<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreorderController extends AbstractController
{
    /**
     * @Route("/preorder", name="preorder")
     */
    public function index(): Response
    {
        // fetch product from database where is preorder is on true
        $book = $this->getDoctrine()->getManager()->getRepository(Product::class)->findoneby(array('id' => 5));
        return $this->render('preorder/preorder.html.twig', [
            'book' => $book
        ]);
    }
}
