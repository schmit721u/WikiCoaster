<?php

namespace App\Controller;

use App\Entity\Coaster;
use App\Form\CoasterType;
use App\Repository\CoasterRepository;
use Doctrine\DBAL\Driver\Mysqli\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoasterController extends AbstractController
{
    #[Route(path: '/coaster')]
    public function index(CoasterRepository $coasterRepository): Response
    {
        // Récupére toutes les entités Coaster
        $entities = $coasterRepository->findAll();

        return $this->render('coaster/index.html.twig', [
            'entities' => $entities, // Envoi des entités dans la vue
        ]);
    }

    #[Route(path: '/coaster/add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $coaster = new Coaster();

        $form = $this->createForm(CoasterType::class, $coaster);
        // Injecter les données $_POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($coaster);
            $em->flush();

            // redirection
        }

        return $this->render('/coaster/add.html.twig', [
            'coasterForm' => $form, // Envoi du formulaire dans la vue
        ]);
    }

    // {id<\d+>} est un paramètre de type entier de 1 ou plusieurs chiffres
    // Symfony utilise le param converter pour trouver l'entité Coaster depuis l'id 
    #[Route(path: '/coaster/{id<\d+>}/edit')]
    public function edit(Coaster $entity, EntityManagerInterface $em, Request $request): Response
    {
        // dump($entity);
        $form = $this->createForm(CoasterType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('/coaster/edit.html.twig', [
            'coasterForm' => $form,
        ]);
    }
}
