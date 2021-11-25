<?php

namespace App\Controller\User;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAddressController extends AbstractController
{
    /**
     * @Route("/user/address", name="user_address")
     */
    public function index(): Response
    {   
        $user = $this->getUser();
        $addresses = $user->getAddresses();
        return $this->render('user/user_address/useraddress.html.twig', [
            'addresses' => $addresses
        ]);
    }

    /**
     * @Route("/user/address/add", name="user_address_add")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        // create new adress and set current connected user owner of the adress
        $address = new Address;
        $user = $this->getUser();
        $address->setUser($user);

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($address);
            $em->flush();

            $this->addFlash(
                'info',
                'Votre adresse a bien été ajouté !'
            );

            return $this->redirectToRoute('user_address');
        }

        return $this->render('user/user_address/useraddressadd.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/address/edit/{id}", name="user_address_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Address $address): Response
    {
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
                $em->flush();

                $this->addFlash(
                    'info',
                    'Votre adresse a bien été modifié !'
                );
    
                return $this->redirectToRoute('user_address');
            }
    
            return $this->render('user/user_address/useraddressedit.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/user/address/delete/{id}", name="user_address_delete")
     */
    public function delete(EntityManagerInterface $em, Address $address): Response
    {
        $user = $this->getUser();

        // verification if user request the suppression is the owner of the adress
        if ($address->getUser() != $user)
        {
            throw $this->createAccessDeniedException();
        }
        else
        {
            $em->remove($address);
            $em->flush();

            $this->addFlash(
                'info',
                'Votre adresse a bien été supprimé !'
            );

            return $this->redirectToRoute('user_address');
        }
    }
}

