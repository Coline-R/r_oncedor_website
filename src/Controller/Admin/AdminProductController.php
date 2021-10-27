<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Type;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{

    /**
     * @Route("/admin/product", name="admin_product")
     */
    public function index(): Response
    {
        // Fetch all products for display
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('admin/admin_product/product.html.twig', [
            'products' => $products
        ]);
    }

     /**
     * @Route("/admin/product/add", name="admin_product_add")
     */
    public function add(Request $request): Response
    {
        $product = new Product;
        $product->setIsPreorder(false);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/admin_product/product_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/admin/product/edit/{id}", name="admin_product_edit")
     */
    public function edit($id, Request $request): Response
    {
        // Fetch product by ID for modification
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/admin_product/type_product.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/delete/{id}", name="admin_product_delete")
     */
    public function delete($id)
    {
        // Fetch prodduct by ID for delete
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('admin_product');
    }
}
