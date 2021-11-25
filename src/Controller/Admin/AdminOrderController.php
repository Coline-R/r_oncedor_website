<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function details(Order $order): Response
    {
        $orderLines = $order->getOrderLines();

        return $this->render('admin/admin_order/adminorderdetails.html.twig', [
            'orderLines' => $orderLines
        ]);
    }

    /**
     * @Route("/admin/order/shipping/{id}", name="admin_order_shipping")
     */
    public function shipping(Order $order, EntityManagerInterface $em): Response
    {
        $order->setIsShipped(true);
        $em->flush($order);

        $this->addFlash(
            'info',
            'La commande a bien été marquée comme envoyé'
        );

        return $this->redirectToRoute('admin_order');
    }
}
