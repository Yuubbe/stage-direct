<?php

namespace App\Controller;

use App\Entity\Internship;
use App\Form\InternshipType;
use App\Repository\InternshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/internship')]
final class InternshipController extends AbstractController
{
    #[Route(name: 'app_internship_index', methods: ['GET'])]
    public function index(InternshipRepository $internshipRepository): Response
    {
        return $this->render('internship/index.html.twig', [
            'internships' => $internshipRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_internship_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $internship = new Internship();
        $form = $this->createForm(InternshipType::class, $internship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($internship);
            $entityManager->flush();

            return $this->redirectToRoute('app_internship_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('internship/new.html.twig', [
            'internship' => $internship,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_internship_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Internship $internship, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $reportContent = $request->request->get('report_content'); // Récupérer le contenu du rapport depuis le formulaire

            // Mettre à jour le contenu du rapport directement dans Internship
            $internship->setReportContent($reportContent); // Assurez-vous d'avoir un champ pour le contenu dans Internship
            $internship->setCreatedBy($this->getUser()); // Associer l'utilisateur qui a créé le stage

            $entityManager->persist($internship);
            $entityManager->flush();

            return $this->redirectToRoute('app_internship_show', ['id' => $internship->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('internship/show.html.twig', [
            'internship' => $internship,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_internship_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Internship $internship, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InternshipType::class, $internship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->render('internship/edit.html.twig', [
                'internship' => $internship,
            ]);
        }

        return $this->render('internship/edit.html.twig', [
            'internship' => $internship,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_internship_delete', methods: ['POST'])]
    public function delete(Request $request, Internship $internship, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
        if ($this->isCsrfTokenValid('delete'.$internship->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($internship);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_internship_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/export/pdf', name: 'app_internship_export_pdf', methods: ['GET'])]
    public function exportPdf(Internship $internship): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('internship/pdf.html.twig', [
            'internship' => $internship,
        ]);

        // Ajoute ceci pour vérifier le HTML généré
        file_put_contents('test_pdf.html', $html);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="internship_'.$internship->getId().'.pdf"');

        return $response;
    }

    #[Route('/pending', name: 'app_internship_pending', methods: ['GET'])]
    public function pending(InternshipRepository $internshipRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // Récupérer uniquement les internships en attente
        $internships = $internshipRepository->findBy(['isPending' => true]);

        return $this->render('internship/pending.html.twig', [
            'internships' => $internships,
        ]);
    }

    public function createInternship(Request $request): Response
    {
        $internship = new Internship();
        // ... autres champs à remplir ...

        // Définir l'utilisateur courant comme créateur
        $user = $this->getUser();
        if ($user) {
            // Affichez l'ID de l'utilisateur pour le débogage
            dump($user->getId());
        } else {
            dump('Aucun utilisateur connecté');
        }
        $internship->setCreatedBy($user);

        // Enregistrer l'internship
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($internship);
        $entityManager->flush();

        // Rediriger ou retourner une réponse
    }
}