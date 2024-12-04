<?php

// src/Controller/InternshipReportController.php
namespace App\Controller;

use App\Entity\IntershipReportEntity;
use App\Form\IntershipReportEntityType;
use App\Repository\IntershipReportEntityRepository;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/internship')]
final class InternshipReportController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PdfGenerator $pdfGenerator;

    // Injection de dépendances pour EntityManager et PdfGenerator
    public function __construct(EntityManagerInterface $entityManager, PdfGenerator $pdfGenerator)
    {
        $this->entityManager = $entityManager;
        $this->pdfGenerator = $pdfGenerator;
    }

    #[Route(name: 'app_internship_report_index', methods: ['GET'])]
    public function index(IntershipReportEntityRepository $intershipReportEntityRepository): Response
    {
        return $this->render('internship_report/index.html.twig', [
            'intership_report_entities' => $intershipReportEntityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_internship_report_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $intershipReportEntity = new IntershipReportEntity();
        $form = $this->createForm(IntershipReportEntityType::class, $intershipReportEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($intershipReportEntity);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_internship_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('internship_report/new.html.twig', [
            'intership_report_entity' => $intershipReportEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_internship_report_show', methods: ['GET'])]
    public function show(IntershipReportEntity $intershipReportEntity): Response
    {
        return $this->render('internship_report/show.html.twig', [
            'intership_report_entity' => $intershipReportEntity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_internship_report_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IntershipReportEntity $intershipReportEntity): Response
    {
        $form = $this->createForm(IntershipReportEntityType::class, $intershipReportEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_internship_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('internship_report/edit.html.twig', [
            'intership_report_entity' => $intershipReportEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_internship_report_delete', methods: ['POST'])]
    public function delete(Request $request, IntershipReportEntity $intershipReportEntity): Response
    {
        if ($this->isCsrfTokenValid('delete' . $intershipReportEntity->getId(), $request->get('token'))) {
            $this->entityManager->remove($intershipReportEntity);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_internship_report_index', [], Response::HTTP_SEE_OTHER);
    }

    // Action pour générer et télécharger le PDF
    #[Route('/{id}/pdf', name: 'app_internship_report_pdf', methods: ['GET'])]
    public function downloadPdf(int $id): Response
    {
        // Récupérer l'entité par son ID
        $intershipReportEntity = $this->entityManager->getRepository(IntershipReportEntity::class)->find($id);

        if (!$intershipReportEntity) {
            throw $this->createNotFoundException('Le rapport demandé n\'existe pas.');
        }

        // Générer le contenu HTML du PDF à partir du template Twig
        $html = $this->renderView('internship_report/pdf_template.html.twig', [
            'contenu' => $intershipReportEntity->getContenu(),
        ]);

        // Générer le PDF via le service PdfGenerator
        $pdfFilePath = $this->pdfGenerator->generate($html, 'rapport_stage_' . $id . '.pdf');

        // Retourner le PDF au navigateur en réponse
        return new Response(
            file_get_contents($pdfFilePath),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="rapport_stage_' . $id . '.pdf"',
            ]
        );
    }
}
