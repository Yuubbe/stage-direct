<?php

namespace App\Controller;

use App\Entity\School;
use App\Form\SchoolType;
use App\Repository\SchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Route('/school')]
final class SchoolController extends AbstractController
{
    #[Route(name: 'app_school_index', methods: ['GET'])]
    public function index(SchoolRepository $schoolRepository): Response
    {
        return $this->render('school/index.html.twig', [
            'schools' => $schoolRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_school_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $school = new School();
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($school);
            $entityManager->flush();

            return $this->redirectToRoute('app_school_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('school/new.html.twig', [
            'school' => $school,
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_school_export', methods: ['GET'])]
    public function exportToExcel(SchoolRepository $schoolRepository): StreamedResponse
    {
        $schools = $schoolRepository->findAll();

        $response = new StreamedResponse(function () use ($schools) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // En-têtes des colonnes
            $sheet->setCellValue('A1', 'Id');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Address');
            $sheet->setCellValue('D1', 'Zipcode');
            $sheet->setCellValue('E1', 'Town');

            $row = 2;
            foreach ($schools as $school) {
                $sheet->setCellValue('A' . $row, $school->getId());
                $sheet->setCellValue('B' . $row, $school->getName());
                $sheet->setCellValue('C' . $row, $school->getAddress());
                $sheet->setCellValue('D' . $row, $school->getZipcode());
                $sheet->setCellValue('E' . $row, $school->getTown());
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="schools_export.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    #[Route('/{id}', name: 'app_school_show', methods: ['GET'])]
    public function show(School $school): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
        return $this->render('school/show.html.twig', [
            'school' => $school,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_school_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, School $school, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_school_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('school/edit.html.twig', [
            'school' => $school,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_school_delete', methods: ['POST'])]
    public function delete(Request $request, School $school, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
        if ($this->isCsrfTokenValid('delete' . $school->getId(), $request->request->get('_token'))) {
            $entityManager->remove($school);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_school_index', [], Response::HTTP_SEE_OTHER);
    }
}
