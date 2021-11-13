<?php

namespace App\Controller\User;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserOrderController extends AbstractController
{
    /**
     * @Route("/user/order", name="user_order")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $orders = $user->getOrders();

        return $this->render('/user/user_order/userorder.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/user/order/details/{id}", name="user_order_details")
     */
    public function details($id, OrderRepository $orderRepo): Response
    {
        $order = $orderRepo->find($id);
        $orderLines = $order->getOrderLines();

        return $this->render('/user/user_order/userorderdetails.html.twig', [
            'orderLines' => $orderLines
        ]);
    }
}
