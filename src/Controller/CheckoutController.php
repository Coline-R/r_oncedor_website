<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout/address", name="checkout_address")
     */
    public function index(Request $request, SessionInterface $session): Response
    {
        //  fetch user and adresses of this user
        $user = $this->getUser();
        $addresses = $user->getAddresses();
        
        // create form for choose deliver address
        $form = $this->createFormBuilder()
            ->add('addresses', ChoiceType::class, [
                'choices' => $addresses,
                'choice_label' => 'name'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // stock choosing address into session before paiement
            $data = $form->getData();
           
            $session->set('deliverAddress',$data['addresses']);


            return $this->redirectToRoute('checkout_recap');
        }  

        return $this->render('checkout/checkout.html.twig', [
            'addresses' => $addresses,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout/recap", name="checkout_recap")
     */
    public function recap(SessionInterface $session, ProductRepository $productRepo): Response
    {
        $deliverAddress = $session->get('deliverAddress', []);
        $productRepo = $this->getDoctrine()->getRepository(Product::class);
        
        $orderCart = $session->get('cart', []);

        $cartData = [];
        $totalCartPrice = 0;

        foreach($orderCart as $id=>$quantity)
        {
            $product = $productRepo->find($id);
            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            
            $totalCartPrice += $product->getPrice() * $quantity;
        }

        return $this->render('checkout/recap.html.twig', [
            'deliverAdress' => $deliverAddress,
            'orderCart' => $cartData
        ]);
    }

    /**
     * @Route("/checkout/payement", name="checkout_payement")
     */
    // public function payement()
    // {

    // }
}
