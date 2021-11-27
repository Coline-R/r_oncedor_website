<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreorderController extends AbstractController
{
    /**
     * @Route("/preorder", name="preorder")
     */
    public function index(ProductRepository $productRepo): Response
    {
        // fetch product from database where is preorder is on true
        $book = $productRepo->findoneby(array('id' => 1));
        return $this->render('preorder/preorder.html.twig', [
            'book' => $book
        ]);
    }
}
