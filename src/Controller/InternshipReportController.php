<?php

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

#[Route('/internship-report')]
final class InternshipReportController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PdfGenerator $pdfGenerator;

    public function __construct(EntityManagerInterface $entityManager, PdfGenerator $pdfGenerator)
    {
        $this->entityManager = $entityManager;
        $this->pdfGenerator = $pdfGenerator;
    }

    #[Route(name: 'app_internship_report_index', methods: ['GET'])]
    public function index(IntershipReportEntityRepository $repository): Response
    {
        return $this->render('internship_report/index.html.twig', [
            'intership_report_entities' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_internship_report_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $entity = new IntershipReportEntity();
        $form = $this->createForm(IntershipReportEntityType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_internship_report_index');
        }

        return $this->render('internship_report/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_internship_report_show', methods: ['GET'])]
    public function show(IntershipReportEntity $entity): Response
    {
        return $this->render('internship_report/show.html.twig', [
            'entity' => $entity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_internship_report_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IntershipReportEntity $entity): Response
    {
        $form = $this->createForm(IntershipReportEntityType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_internship_report_index');
        }

        return $this->render('internship_report/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_internship_report_delete', methods: ['POST'])]
    public function delete(Request $request, IntershipReportEntity $entity): Response
    {
        if ($this->isCsrfTokenValid('delete' . $entity->getId(), $request->get('_token'))) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_internship_report_index');
    }

    #[Route('/{id}/pdf', name: 'app_internship_report_pdf', methods: ['GET'])]
    public function downloadPdf(IntershipReportEntity $entity): Response
    {
        if (!$entity) {
            throw $this->createNotFoundException('Le rapport demandé n\'existe pas.');
        }

        $html = $this->renderView('internship_report/pdf_template.html.twig', [
            'entity' => $entity,
        ]);

        $pdfFilePath = $this->pdfGenerator->generate($html, 'rapport_stage_' . $entity->getId() . '.pdf');

        return new Response(
            file_get_contents($pdfFilePath),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="rapport_stage_' . $entity->getId() . '.pdf"',
            ]
        );
    }
}
