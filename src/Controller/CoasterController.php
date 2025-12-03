<?php

namespace App\Controller;

use App\Entity\Coaster;
use App\Form\CoasterType;
use App\Repository\CategorieRepository;
use App\Repository\CoasterRepository;
use App\Repository\ParkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoasterController extends AbstractController
{
    #[Route(path: '/coaster')]
    public function index(
        CoasterRepository $coasterRepository,
        ParkRepository $parkRepository,
        CategorieRepository $categorieRepository,
        Request $request,
    ): Response
    {
        // Récupére toutes les entités Coaster
        // $entities = $coasterRepository->findAll();
        $parks = $parkRepository->findAll();
        $categories = $categorieRepository->findAll();

        // Valeurs envoyées depuis le formulaire de filtre
        $parkId = (int) $request->query->get('park');
        $categorieId = (int) $request->query->get('categorie');

        $count = 2;
        $page = (int) $request->query->get('p', 1); // 1 par défaut

        $entities = $coasterRepository->findFiltered($parkId, $categorieId, $count, $page);

        $pageCount = max(ceil($entities->count() / $count), 1);

        return $this->render('coaster/index.html.twig', [
            'entities' => $entities, // Envoi des entités dans la vue
            'parks' => $parks,
            'categories' => $categories,
            'parkId' => $parkId,
            'categorieId' => $categorieId,
            'pageCount' => $pageCount, // Nombre de pages
            'page' => $page, // Numéro de la page à afficher
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
            return $this->redirectToRoute('app_coaster_index');
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

            return $this->redirectToRoute('app_coaster_index');
        }

        return $this->render('/coaster/edit.html.twig', [
            'coasterForm' => $form,
        ]);
    }

    #[Route(path: '/coaster/{id<\d+>}/delete')]
    public function delete(Coaster $entity, EntityManagerInterface $em, Request $request): Response
    {
        // $_POST['_token'] => $request->request 
        // $_GET['value'] => $request->query 
        // $_SERVER[] => $request->server 
        // $request->get('_token') => Deprecated

        if ($this->isCsrfTokenValid(
            'delete' . $entity->getId(), 
            $request->request->get('_token')
        )) {
            $em->remove($entity);
            $em->flush();

            // symfony console debug:router
            return $this->redirectToRoute('app_coaster_index');
        }

        return $this->render('/coaster/delete.html.twig', [
            'coaster' => $entity,
        ]);
    }
}