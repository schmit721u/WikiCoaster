<?php

namespace App\Form;

use App\Entity\Coaster;
use App\Entity\Park; // <--- 1. Important : Importez l'entité Park
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // <--- 2. Important : Importez le type EntityType
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoasterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('maxSpeed')
            ->add('length')
            ->add('maxHeight')
            ->add('operating')
            ->add('park', EntityType::class, [
                'class' => Park::class,
                'choice_label' => 'name',      
                'label' => 'Park',            
                'placeholder' => 'Choisir un parc', 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coaster::class,
        ]);
    }
}