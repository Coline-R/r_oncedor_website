<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Type;
use App\Entity\Category;
use App\Entity\Edition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom"
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description"
            ])
            ->add('summary', TextareaType::class, [
                'label' => "Résumé"
            ])
            ->add('price', NumberType::class, [
                'label' => "Prix"
            ] )
            ->add('tome', NumberType::class, [
                'label' => "Tome"
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type',
                'class' => Type::class,
                'choice_label' => 'name' 
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name' 
            ])
            ->add('edition', EntityType::class, [
                'label' => 'Edition',
                'class' => Edition::class,
                'choice_label' => 'name' 
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
