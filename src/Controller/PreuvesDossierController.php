<?php
// src/Controller/PreuvesDossierController.php

namespace App\Controller;

use App\Entity\PreuvesDossier;
use App\Form\PreuvesDossierType;
use App\Repository\PreuvesDossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Lawyer;
use App\Entity\User;

#[Route('/preuvesdossier')]
class PreuvesDossierController extends AbstractController
{
    #[Route('/', name: 'app_preuves_dossier_index', methods: ['GET'])]
    public function index(PreuvesDossierRepository $preuvesDossierRepository): Response
    {
        return $this->render('preuves_dossier/index.html.twig', [
            'preuvesDossiers' => $preuvesDossierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_preuves_dossier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $lawyerEmail = $session->get('user_email');

        if (!$lawyerEmail) {
            throw $this->createNotFoundException('No lawyer is logged in');
        }

        $lawyer = $entityManager->getRepository(User::class)->findOneBy(['email' => $lawyerEmail]);

        if (!$lawyer || !$lawyer instanceof Lawyer) {
            throw $this->createNotFoundException('Lawyer not found');
        }

        $preuvesDossier = new PreuvesDossier();
        $form = $this->createForm(PreuvesDossierType::class, $preuvesDossier, ['lawyer' => $lawyer]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($preuvesDossier);
            $entityManager->flush();

            return $this->redirectToRoute('app_preuves_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('preuves_dossier/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_preuves_dossier_show', methods: ['GET'])]
    public function show(PreuvesDossier $preuvesDossier): Response
    {
        return $this->render('preuves_dossier/show.html.twig', [
            'preuvesDossier' => $preuvesDossier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_preuves_dossier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PreuvesDossier $preuvesDossier, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $lawyerEmail = $session->get('user_email');

        if (!$lawyerEmail) {
            throw $this->createNotFoundException('No lawyer is logged in');
        }

        $lawyer = $entityManager->getRepository(User::class)->findOneBy(['email' => $lawyerEmail]);

        if (!$lawyer || !$lawyer instanceof Lawyer) {
            throw $this->createNotFoundException('Lawyer not found');
        }

        $form = $this->createForm(PreuvesDossierType::class, $preuvesDossier, ['lawyer' => $lawyer]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_preuves_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('preuves_dossier/edit.html.twig', [
            'preuvesDossier' => $preuvesDossier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_preuves_dossier_delete', methods: ['POST'])]
    public function delete(Request $request, PreuvesDossier $preuvesDossier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $preuvesDossier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($preuvesDossier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_preuves_dossier_index', [], Response::HTTP_SEE_OTHER);
    }
}
