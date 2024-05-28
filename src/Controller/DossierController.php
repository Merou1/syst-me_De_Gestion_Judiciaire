<?php
// src/Controller/DossierController.php

namespace App\Controller;

use App\Entity\Dossier;
use App\Form\DossierType;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('/dossier')]
class DossierController extends AbstractController
{
    #[Route('/', name: 'app_dossier_index', methods: ['GET'])]
    public function index(DossierRepository $dossierRepository): Response
    {
        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossierRepository->findAll(),
        ]);
    }

    #[Route('/dossier/new', name: 'app_dossier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $dossier = new Dossier();
        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentFiles = $form->get('documents')->getData();

            if ($documentFiles) {
                $documentNames = [];
                foreach ($documentFiles as $documentFile) {
                    $originalFilename = pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $documentFile->guessExtension();

                    try {
                        $documentFile->move(
                            $this->getParameter('documents_directory'),
                            $newFilename
                        );
                        $documentNames[] = $newFilename;
                    } catch (FileException $e) {
                        // Handle exception if something happens during file upload
                    }
                }
                $dossier->setDocuments($documentNames);
            }

            $entityManager->persist($dossier);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_dossier_show', methods: ['GET'])]
    public function show(Dossier $dossier): Response
    {
        return $this->render('dossier/show.html.twig', [
            'dossier' => $dossier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dossier $dossier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier/edit.html.twig', [
            'dossier' => $dossier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_delete', methods: ['POST'])]
    public function delete(Request $request, Dossier $dossier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dossier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dossier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
    }
}
