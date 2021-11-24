<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
     /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index(CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAll();

        return $this->render('admin/admin_category/category.html.twig', [
            'categories' => $categories
        ]);
    }

     /**
     * @Route("/admin/category/add", name="admin_category_add")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'info',
                'L\'ajout de la catégorie a bien été effectué'
            );

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/admin_category/category_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", name="admin_category_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash(
                'info',
                'La modification de la catégorie a bien été effectué'
            );

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/admin_category/category_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="admin_category_delete")
     */
    public function delete(Category $category, EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();

        $this->addFlash(
            'info',
            'La suppression de la catégorie a bien été effectué'
        );

        return $this->redirectToRoute('admin_category');
    }
}
