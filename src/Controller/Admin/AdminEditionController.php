<?php

namespace App\Controller\Admin;

use App\Entity\Edition;
use App\Form\EditionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditionController extends AbstractController
{
     /**
     * @Route("/admin/edition", name="admin_edition")
     */
    public function index(): Response
    {
        // Fetch all editions for display
        $editions = $this->getDoctrine()->getRepository(Edition::class)->findAll();

        return $this->render('admin/admin_edition/edition.html.twig', [
            'editions' => $editions
        ]);
    }

     /**
     * @Route("/admin/edition/add", name="admin_edition_add")
     */
    public function add(Request $request): Response
    {
        $edition = new Edition;

        $form = $this->createForm(EditionType::class, $edition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($edition);
            $em->flush();

            return $this->redirectToRoute('admin_edition');
        }

        return $this->render('admin/admin_edition/edition_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/edition/edit/{id}", name="admin_edition_edit")
     */
    public function edit($id, Request $request): Response
    {
        // Fetch edition by ID for modification
        $edition = $this->getDoctrine()->getRepository(Edition::class)->find($id);

        $form = $this->createForm(EditionType::class, $edition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('admin_edition');
        }

        return $this->render('admin/admin_edition/edition_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/edition/delete/{id}", name="admin_edition_delete")
     */
    public function delete($id)
    {
        // Fetch edition by ID for delete
        $edition = $this->getDoctrine()->getRepository(Edition::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($edition);
        $em->flush();

        return $this->redirectToRoute('admin_edition');
    }
}
