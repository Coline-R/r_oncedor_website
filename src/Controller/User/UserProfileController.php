<?php

namespace App\Controller\User;

use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function index(): Response
    {
        return $this->render('user/user_profile/userprofile.html.twig');
    }

    /**
     * @Route("/user/profile/edit", name="user_profile_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
   
        $form = $this->createForm(UserProfileType::class, $this->getUser());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/user_profile/userprofileedit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
