<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepo): Response
    {
        $cart = $session->get('cart', []);

        $cartData = [];
        $totalCartPrice = 0;

        foreach($cart as $id=>$quantity)
        {
            $product = $productRepo->find($id);
            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            
            $totalCartPrice += $product->getPrice() * $quantity;
        }



        return $this->render('cart/cart.html.twig', [
            'items' => $cartData,
            'totalCartPrice' => $totalCartPrice
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session ): Response
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$id]))
        {
            $cart[$id]++;
        }
        else
        {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session)
    {
        $cart = $session->get('cart');

        if(!empty($cart[$id]))
        {
            if($cart[$id] > 1)
            {
                $cart[$id]--;
            }
            else
            {
                unset($cart[$id]);
            }
        }
        
        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }
}
