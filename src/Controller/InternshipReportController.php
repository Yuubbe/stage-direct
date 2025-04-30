<?php

namespace App\Controller;

use App\Entity\Internship;
use App\Entity\InternshipReport;
use App\Form\InternshipReportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/report')]
class InternshipReportController extends AbstractController
{
    #[Route('/internship/{id}/new', name: 'app_report_new', methods: ['GET', 'POST'])]
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
            'form' => $form,
            'internship' => $internship,
        ]);
    }
}