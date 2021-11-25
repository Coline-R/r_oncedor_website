<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTypeController extends AbstractController
{
    /**
     * @Route("/admin/type", name="admin_type")
     */
    public function index(TypeRepository $typeRepo): Response
    {
        $types = $typeRepo->findAll();

        return $this->render('admin/admin_type/type.html.twig', [
            'types' => $types
        ]);
    }

     /**
     * @Route("/admin/type/add", name="admin_type_add")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $type = new Type;

        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($type);
            $em->flush();

            $this->addFlash(
                'info',
                'L\'ajout du type a bien été effectué'
            );

            return $this->redirectToRoute('admin_type');
        }

        return $this->render('admin/admin_type/type_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/type/edit/{id}", name="admin_type_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Type $type): Response
    {
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash(
                'info',
                'La modification du type a bien été effectué'
            );

            return $this->redirectToRoute('admin_type');
        }

        return $this->render('admin/admin_type/type_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/type/delete/{id}", name="admin_type_delete")
     */
    public function delete(EntityManagerInterface $em, Type $type)
    {
        $em->remove($type);
        $em->flush();

        $this->addFlash(
            'info',
            'La suppression du type a bien été effectué'
        );

        return $this->redirectToRoute('admin_type');
    }
}
