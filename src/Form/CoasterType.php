<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Coaster;
use App\Entity\Park;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoasterType extends AbstractType
{

    public function __construct(
        private AuthorizationCheckerInterface $autorizationChecker,
     ) {}

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
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'label' => 'Categorie',
                'multiple' => true, 
                'expanded' => true, 
                'by_reference' => false,
            ])
        ;

        if  ($this->autorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add('published', options :[
                'label'=>"Publier l'affiche" ,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coaster::class,
        ]);
    }
}