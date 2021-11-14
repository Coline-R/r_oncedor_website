<?php

namespace App\Controller\Admin;

use App\Entity\Opinion;
use App\Form\OpinionType;
use App\Repository\OpinionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOpinionController extends AbstractController
{
    /**
     * @Route("/admin/opinion", name="admin_opinion")
     */
    public function index(OpinionRepository $opinionRepo): Response
    {
        $opinions = $opinionRepo->findAll();

        return $this->render('admin/admin_opinion/opinion.html.twig', [
            'opinions' => $opinions
        ]);
    }

     /**
     * @Route("/admin/opinion/add", name="admin_opinion_add")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $opinion = new Opinion;

        $form = $this->createForm(OpinionType::class, $opinion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
    public function edit(Request $request, EntityManagerInterface $em, Opinion $opinion): Response
    {
        $form = $this->createForm(OpinionType::class, $opinion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
    public function delete(EntityManagerInterface $em, Opinion $opinion)
    {
        $em->remove($opinion);
        $em->flush();

        return $this->redirectToRoute('admin_opinion');
    }
}
