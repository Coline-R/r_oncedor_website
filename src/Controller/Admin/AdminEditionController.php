<?php

namespace App\Controller\Admin;

use App\Entity\Edition;
use App\Form\EditionType;
use App\Repository\EditionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditionController extends AbstractController
{
     /**
     * @Route("/admin/edition", name="admin_edition")
     */
    public function index(EditionRepository $editionRepo): Response
    {
        $editions = $editionRepo->findAll();

        return $this->render('admin/admin_edition/edition.html.twig', [
            'editions' => $editions
        ]);
    }

     /**
     * @Route("/admin/edition/add", name="admin_edition_add")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $edition = new Edition;

        $form = $this->createForm(EditionType::class, $edition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
    public function edit(Request $request, EntityManagerInterface $em, Edition $edition): Response
    {
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
    public function delete(EntityManagerInterface $em, Edition $edition)
    {
        $em->remove($edition);
        $em->flush();

        return $this->redirectToRoute('admin_edition');
    }
}
