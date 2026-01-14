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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class CoasterType extends AbstractType
{

    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
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
            ])
            ->add('image', FileType::class, [
                    'label' => 'Image du coaster',
                    'mapped' => false,     // Ne correspond pas à une propriété de l'entité [cite: 11]
                    'required' => false,   // Le champ n'est pas obligatoire [cite: 12]
                    'constraints' => [     // Sécurisation du type de fichier [cite: 22]
                        new Image()
                    ],
                ])
            ;

        if  ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
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