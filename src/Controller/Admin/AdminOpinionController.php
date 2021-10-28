<?php

namespace App\Controller\Admin;

use App\Entity\Opinion;
use App\Form\OpinionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOpinionController extends AbstractController
{
    /**
     * @Route("/admin/opinion", name="admin_opinion")
     */
    public function index(): Response
    {
        // Fetch all opinions for display
        $opinions = $this->getDoctrine()->getRepository(Opinion::class)->findAll();

        return $this->render('admin/admin_opinion/opinion.html.twig', [
            'opinions' => $opinions
        ]);
    }

     /**
     * @Route("/admin/opinion/add", name="admin_opinion_add")
     */
    public function add(Request $request): Response
    {
        $opinion = new Opinion;

        $form = $this->createForm(OpinionType::class, $opinion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($opinion);
            $em->flush();

            return $this->redirectToRoute('admin_opinion');
        }

        return $this->render('admin/admin_opinion/opinion_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/admin/opinion/edit/{id}", name="admin_opinion_edit")
     */
    public function edit($id, Request $request): Response
    {
        // Fetch opinion by ID for modification
        $opinion = $this->getDoctrine()->getRepository(Opinion::class)->find($id);

        $form = $this->createForm(OpinionType::class, $opinion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('admin_opinion');
        }

        return $this->render('admin/admin_opinion/opinion_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/opinion/delete/{id}", name="admin_opinion_delete")
     */
    public function delete($id)
    {
        // Fetch opinion by ID for delete
        $opinion = $this->getDoctrine()->getRepository(Opinion::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($opinion);
        $em->flush();

        return $this->redirectToRoute('admin_opinion');
    }
}
