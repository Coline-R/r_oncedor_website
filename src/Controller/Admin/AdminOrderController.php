<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /**
     * @Route("/admin/order", name="admin_order")
     */
    public function index(OrderRepository $orderRepo): Response
    {
        $orderShipped = $orderRepo->findBy(['isShipped' => true]);
        $orderNoShipped = $orderRepo->findBy(['isShipped' => false]);

        return $this->render('admin/admin_order/adminorder.html.twig', [
            'shippedOrder' => $orderShipped,
            'noShippedOrder' => $orderNoShipped
        ]);
    }

    /**
     * @Route("/admin/order/details/{id}", name="admin_order_details")
     */
    public function details($id, OrderRepository $orderRepo): Response
    {
        $order = $orderRepo->find($id);
        $orderLines = $order->getOrderLines();

        return $this->render('admin/admin_order/adminorderdetails.html.twig', [
            'orderLines' => $orderLines
        ]);
    }

    /**
     * @Route("/admin/order/shipping/{id}", name="admin_order_shipping")
     */
    public function shipping($id, OrderRepository $orderRepo): Response
    {
        $em = $this->getDoctrine()->getManager();

        $order = $orderRepo->find($id);
        $order->setIsShipped(true);
        $em->flush($order);

        return $this->redirectToRoute('admin_order');
    }
}
