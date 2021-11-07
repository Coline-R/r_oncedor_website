<?php

namespace App\Controller\User;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdressController extends AbstractController
{
    /**
     * @Route("/user/adress", name="user_address")
     */
    public function index(): Response
    {   
        $user = $this->getUser();
        $adresses = $user->getAddresses();
        return $this->render('user/user_adress/useradress.html.twig', [
            'adresses' => $adresses
        ]);
    }

    /**
     * @Route("/user/adress/add", name="user_address_add")
     */
    public function add(Request $request): Response
    {
        // create new adress and set current connected user owner of the adress
        $address = new Address;
        $user = $this->getUser();
        $address->setUser($user);

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('user_address');
        }

        return $this->render('user/user_adress/useradressadd.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/adress/edit/{id}", name="user_adress_edit")
     */
    public function edit($id, Request $request): Response
    {
        $address = $this->getDoctrine()->getRepository(Address::class)->find($id);
        $user = $this->getUser();

        // verification if user request the modification is the owner of the adress
        if ($address->getUser() != $user)
        {
            throw $this->createAccessDeniedException();
        }
        else
        {
            $form = $this->createForm(AddressType::class, $address);
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid() && $address->getUser() == $user )
            {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
    
                return $this->redirectToRoute('user_address');
            }
    
            return $this->render('user/user_adress/useradressedit.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/user/adress/delete/{id}", name="user_adress_delete")
     */
    public function delete($id): Response
    {
        $address = $this->getDoctrine()->getRepository(Address::class)->find($id);
        $user = $this->getUser();

        // verification if user request the suppression is the owner of the adress
        if ($address->getUser() != $user)
        {
            throw $this->createAccessDeniedException();
        }
        else
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush();

            return $this->redirectToRoute('user_address');
        }
    }
}

