<?php

namespace App\Controller\Checkout;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

        return $this->render('checkout/checkout/checkout.html.twig', [
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

        return $this->render('checkout/checkout/recap.html.twig', [
            'deliverAdress' => $deliverAddress,
            'orderCart' => $cartData
        ]);
    }

    /**
     * @Route("/checkout/payement", name="checkout_payement")
     */
    public function payement(SessionInterface $session, ProductRepository $productRepo, $stripeSK): Response
    {
        // Stripe APIKey
        Stripe::setApiKey($stripeSK);

        // Cart informations
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

        // Create stripe payement intent
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
              'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                  'name' => 'Votre précommande sur le site ...',
                ],
                'unit_amount' => $totalCartPrice * 100,
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('checkout_success',[], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('checkout_cancel',[], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);

          return $this->redirect($session->url, 303);
    }

    /**
     * @Route("/checkout/success", name="checkout_success")
     */
    public function success(SessionInterface $session, ProductRepository $productRepo, AddressRepository $addressRepo, OrderRepository $orderRepo, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        // register order in database
        $order = new Order;

        $address = $session->get('deliverAddress', []);
        $deliverAddress = $addressRepo->find($address->getId());

        $order->setOrderDate(new DateTime());
        $order->setAddress($deliverAddress);
        $order->setUser($this->getUser());
        $order->setIsShipped(false);

        $em->persist($order);
        $em->flush();

        // catch order id and register all order line in database
        $orderCart = $session->get('cart', []);
        $orderId = $orderRepo->find($order->getId());


        foreach ($orderCart as $id=>$quantity) {
            $product = $productRepo->find($id);
            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }

        for ($i = 0 ; $i < count($cartData) ; $i++) {
            if ($cartData[$i]['quantity'] == 1) {
                $orderLine = new OrderLine;

                $productId = $productRepo->find($cartData[$i]['product']->getId());
                $orderLine->setProduct($productId);
                $orderLine->setCommand($orderId);

                $em->persist($orderLine);
                $em->flush();
            } else {
                for ($j = 0 ; $j < $cartData[$i]['quantity']; $j++) {
                    $orderLine = new OrderLine;

                    $productId = $productRepo->find($cartData[$i]['product']->getId());
                    $orderLine->setProduct($productId);
                    $orderLine->setCommand($orderId);

                    $em->persist($orderLine);
                    $em->flush();
                }
            }
        }
        
        // send order confirmation mail to the user
        $email = (new TemplatedEmail())
            ->from('no-reply@dev-r-oncedor.fr')
            ->to(new Address($this->getUser()->getEmail()))
            ->subject('Confirmation de votre commande')

            ->htmlTemplate('checkout/checkout/confirm_order.html.twig')

            ->context(['order' => $order])
        ;

        $mailer->send($email);

        return $this->render('checkout/checkout/checkout_success.html.twig');
    }

    /**
     * @Route("/checkout/cancel", name="checkout_cancel")
     */
    public function cancel(): Response
    {
        return $this->render('checkout/checkout/checkout_cancel.html.twig');
    }
}
