<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
     /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index(): Response
    {
        // Fetch all categories for display
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('admin/admin_category/category.html.twig', [
            'categories' => $categories
        ]);
    }

     /**
     * @Route("/admin/category/add", name="admin_category_add")
     */
    public function add(Request $request): Response
    {
        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/admin_category/category_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", name="admin_category_edit")
     */
    public function edit($id, Request $request): Response
    {
        // Fetch category by ID for modification
        $category= $this->getDoctrine()->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/admin_category/category_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="admin_category_delete")
     */
    public function delete($id)
    {
        // Fetch category by ID for delete
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_category');
    }
}
