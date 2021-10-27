<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Form\TypeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTypeController extends AbstractController
{
    /**
     * @Route("/admin/type", name="admin_type")
     */
    public function index(): Response
    {
        // Fetch all types for display
        $types = $this->getDoctrine()->getRepository(Type::class)->findAll();

        return $this->render('admin/admin_type/type.html.twig', [
            'types' => $types
        ]);
    }

     /**
     * @Route("/admin/type/add", name="admin_type_add")
     */
    public function add(Request $request): Response
    {
        $type = new Type;

        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($type);
            $em->flush();

            return $this->redirectToRoute('admin_type');
        }

        return $this->render('admin/admin_type/type_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/type/edit/{id}", name="admin_type_edit")
     */
    public function edit($id, Request $request): Response
    {
        // Fetch type by ID for modification
        $type = $this->getDoctrine()->getRepository(Type::class)->find($id);

        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('admin_type');
        }

        return $this->render('admin/admin_type/type_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/type/delete/{id}", name="admin_type_delete")
     */
    public function delete($id)
    {
        // Fetch type by ID for delete
        $type = $this->getDoctrine()->getRepository(Type::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($type);
        $em->flush();

        return $this->redirectToRoute('admin_type');
    }
}
