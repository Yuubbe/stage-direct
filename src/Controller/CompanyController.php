<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Repository\SectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Route('/company')]
final class CompanyController extends AbstractController
{
    #[Route(name: 'app_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $companies = $companyRepository->findAll();
        
        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/pending', name: 'app_company_pending', methods: ['GET'])]
    public function pending(CompanyRepository $companyRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
        $pendingCompanies = $companyRepository->findBy(['status' => Company::STATUS_PENDING]);
        
        return $this->render('company/pending.html.twig', [
            'companies' => $pendingCompanies,
        ]);
    }

    #[Route('/{id}/approve', name: 'app_company_approve', methods: ['POST'])]
    public function approve(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
        
        if ($this->isCsrfTokenValid('approve'.$company->getId(), $request->request->get('_token'))) {
            $company->setStatus(Company::STATUS_APPROVED);
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'entreprise a été approuvée.');
        }
        
        return $this->redirectToRoute('app_company_pending');
    }

    #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $company = new Company();
        $company->setStatus(Company::STATUS_PENDING);
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash('success', 'L\'entreprise a été créée avec succès.');
            return $this->redirectToRoute('app_company_index');
        }

        return $this->render('company/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_company_export', methods: ['GET'])]
    public function exportToExcel(CompanyRepository $companyRepository): StreamedResponse
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
        // Récupérer toutes les entreprises
        $companies = $companyRepository->findAll();

        // Créer une réponse "streamed" pour générer le fichier dynamiquement
        $response = new StreamedResponse(function () use ($companies) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Ajouter les en-têtes
            $sheet->setCellValue('A1', 'Id');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Address');
            $sheet->setCellValue('D1', 'Tel');
            $sheet->setCellValue('E1', 'Mail');
            $sheet->setCellValue('F1', 'Zipcode');

            // Ajouter les données des entreprises
            $row = 2;
            foreach ($companies as $company) {
                $sheet->setCellValue('A' . $row, $company->getId());
                $sheet->setCellValue('B' . $row, $company->getName());
                $sheet->setCellValue('C' . $row, $company->getAddress());
                $sheet->setCellValue('D' . $row, $company->getTel());
                $sheet->setCellValue('E' . $row, $company->getMail());
                $sheet->setCellValue('F' . $row, $company->getZipcode());
                $row++;
            }

            // Créer l'objet Writer
            $writer = new Xlsx($spreadsheet);

            // Sauvegarder dans php://output
            $writer->save('php://output');
        });

        // Configurer les en-têtes de la réponse
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="companies_export.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager, SectorRepository $sectorRepository): Response
    {
        $form = $this->createForm(CompanyType::class, $company, [
            'sector_repository' => $sectorRepository
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'entreprise a été mise à jour avec succès.');
            
            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $entityManager->remove($company);
            $entityManager->flush();

            $this->addFlash('success', 'L\'entreprise a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
    }
}
