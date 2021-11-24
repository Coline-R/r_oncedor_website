<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Type;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{

    /**
     * @Route("/admin/product", name="admin_product")
     */
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();

        return $this->render('admin/admin_product/product.html.twig', [
            'products' => $products
        ]);
    }

     /**
     * @Route("/admin/product/add", name="admin_product_add")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product;
        $product->setIsPreorder(false);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'info',
                'L\'ajout du produit a bien été effectué'
            );

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/admin_product/product_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/admin/product/edit/{id}", name="admin_product_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash(
                'info',
                'La modification du produit a bien été effectué'
            );

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/admin_product/product_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/delete/{id}", name="admin_product_delete")
     */
    public function delete(EntityManagerInterface $em, Product $product)
    {
        $em->remove($product);
        $em->flush();

        $this->addFlash(
            'info',
            'La supression du produit a bien été effectué'
        );

        return $this->redirectToRoute('admin_product');
    }
}
