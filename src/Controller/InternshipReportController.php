<?php

namespace App\Controller;

use App\Entity\Internship;
use App\Entity\InternshipReport;
use App\Form\InternshipReportType;
use App\Repository\InternshipReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/internship-report')]
class InternshipReportController extends AbstractController
{
    #[Route('/', name: 'internship_report_index', methods: ['GET'])]
    public function index(InternshipReportRepository $internshipReportRepository): Response
    {
        return $this->render('internship_report/index.html.twig', [
            'internship_reports' => $internshipReportRepository->findAll(),
        ]);
    }

    #[Route('/internship/{id}/report/new', name: 'app_report_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Internship $internship, EntityManagerInterface $entityManager): Response
    {
        $report = new InternshipReport();
        $report->setInternship($internship);
        $report->setSubmissionDate(new \DateTime());

        $form = $this->createForm(InternshipReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($report);
            $entityManager->flush();

            $this->addFlash('success', 'Le rapport a été créé avec succès.');
            return $this->redirectToRoute('app_internship_show', ['id' => $internship->getId()]);
        }

        return $this->render('internship_report/new.html.twig', [
            'form' => $form->createView(),
            'internship' => $internship,
        ]);
    }

    #[Route('/{id}', name: 'internship_report_show', methods: ['GET'])]
    public function show(InternshipReport $internshipReport): Response
    {
        return $this->render('internship_report/show.html.twig', [
            'internship_report' => $internshipReport,
        ]);
    }

    #[Route('/{id}/edit', name: 'internship_report_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InternshipReport $internshipReport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InternshipReportType::class, $internshipReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('internship_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('internship_report/edit.html.twig', [
            'internship_report' => $internshipReport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'internship_report_delete', methods: ['POST'])]
    public function delete(Request $request, InternshipReport $internshipReport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$internshipReport->getId(), $request->request->get('_token'))) {
            $entityManager->remove($internshipReport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('internship_report_index', [], Response::HTTP_SEE_OTHER);
    }
}
