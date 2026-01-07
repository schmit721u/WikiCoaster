<?php

namespace App\Controller;

use App\Entity\Coaster;
use App\Form\CoasterType;
use App\Service\FileUploader;
use App\Repository\CategorieRepository;
use App\Repository\CoasterRepository;
use App\Repository\ParkRepository;
use App\Security\Voter\CoasterVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        $parks = $parkRepository->findAll();
        $categories = $categorieRepository->findAll();

        $parkId = (int) $request->query->get('park');
        $categorieId = (int) $request->query->get('categorie');

        $count = 2;
        $page = (int) $request->query->get('p', 1);

        $entities = $coasterRepository->findFiltered($parkId, $categorieId, $count, $page);

        $pageCount = max(ceil($entities->count() / $count), 1);

        return $this->render('coaster/index.html.twig', [
            'entities' => $entities,
            'parks' => $parks,
            'categories' => $categories,
            'parkId' => $parkId,
            'categorieId' => $categorieId,
            'pageCount' => $pageCount,
            'page' => $page,
        ]);
    }

    #[Route(path: '/coaster/add')]
    #[IsGranted('ROLE_USER')] 
    public function add(EntityManagerInterface $em, Request $request, FileUploader $fileUploader): Response
    {
        $coaster = new Coaster();
        $coaster->setAuthor($this->getUser());

        $form = $this->createForm(CoasterType::class, $coaster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = $fileUploader->upload($imageFile);
                $coaster->setImageFileName($newFilename);
            }

            $em->persist($coaster);
            $em->flush();

            return $this->redirectToRoute('app_coaster_index');
        }

        return $this->render('/coaster/add.html.twig', [
            'coasterForm' => $form,
        ]);
    }

    #[Route(path: '/coaster/{id<\d+>}/edit')]
    #[IsGranted('ROLE_USER')] 
    public function edit(Coaster $entity, EntityManagerInterface $em, Request $request, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted(CoasterVoter::EDIT, $entity);

        $form = $this->createForm(CoasterType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                if ($entity->getImageFileName()) {
                    $fileUploader->remove($entity->getImageFileName());
                }
                $newFilename = $fileUploader->upload($imageFile);
                $entity->setImageFileName($newFilename);
            }

            $em->flush();

            return $this->redirectToRoute('app_coaster_index');
        }

        return $this->render('/coaster/edit.html.twig', [
            'coasterForm' => $form,
        ]);
    }

    #[Route(path: '/coaster/{id<\d+>}/delete')]
    #[IsGranted('ROLE_ADMIN')] 
    public function delete(Coaster $entity, EntityManagerInterface $em, Request $request, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted(CoasterVoter::EDIT, $entity);

        if ($this->isCsrfTokenValid(
            'delete' . $entity->getId(), 
            $request->request->get('_token')
        )) {
            if ($entity->getImageFileName()) {
                $fileUploader->remove($entity->getImageFileName());
            }

            $em->remove($entity);
            $em->flush();

            return $this->redirectToRoute('app_coaster_index');
        }

        return $this->render('/coaster/delete.html.twig', [
            'coaster' => $entity,
        ]);
    }

    #[Route('/coaster/{id<\d+>}', methods: ['GET'])]
    public function show(Coaster $entity): Response
    {
        $this->denyAccessUnlessGranted(CoasterVoter::VIEW, $entity);

        return $this->render('/coaster/show.html.twig', [
            'entity' => $entity,
        ]);
    }
}