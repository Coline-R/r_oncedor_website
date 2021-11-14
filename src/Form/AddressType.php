<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'adresse',
                'attr' => ['placeholder' => 'Le nom de l\'adresse']
            ])
            ->add('lineOne', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'Votre adresse']
            ])
            ->add('lineTwo', TextType::class, [
                'label' => 'Complément d\'adresse',
                'attr' => ['placeholder' => 'Votre complément d\'adresse']
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => ['placeholder' => 'Votre code postal']
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Votre ville']
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'attr' => ['placeholder' => 'Votre pays']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
