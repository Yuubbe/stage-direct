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
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/company')]
final class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_company_index', methods: ['GET'])]
    public function index(Request $request, CompanyRepository $companyRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Récupérer le terme de recherche
        $searchTerm = $request->query->get('search', '');

        // Rechercher les entreprises correspondant au terme
        $queryBuilder = $companyRepository->createQueryBuilder('c');
        if (!empty($searchTerm)) {
            $queryBuilder->where('c.name LIKE :searchTerm OR c.street LIKE :searchTerm OR c.city LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Pagination
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Query
            $request->query->getInt('page', 1), // Numéro de la page
            5 // Nombre d'éléments par page
        );

        return $this->render('company/index.html.twig', [
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Vérifie que l'utilisateur a le rôle 'ROLE_ADMIN'

        // Vérifie la validité du jeton CSRF
        if ($this->isCsrfTokenValid('approve' . $company->getId(), $request->request->get('_token'))) {
            $company->setIsApproved(true); // Marque l'entreprise comme approuvée
            $entityManager->flush();

            $this->addFlash('success', 'L\'entreprise a été approuvée avec succès.');
        } else {
            // Ajoute un message flash d'erreur si le jeton CSRF est invalide
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        // Redirige vers la liste des entreprises
        return $this->redirectToRoute('app_company_index');
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
        $form = $this->createForm(CompanyType::class, $company);
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

    #[Route('/company/approve-all', name: 'app_company_approve_all', methods: ['POST'])]
    public function approveAll(EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Vérifie que l'utilisateur a le rôle 'ROLE_ADMIN'

        $request = Request::createFromGlobals();
        if (!$this->isCsrfTokenValid('approve_all', $request->request->get('_token'))) {
            $this->addFlash('error', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_company_index');
        }

        // Récupère toutes les entreprises non approuvées
        $companies = $companyRepository->findBy(['isApproved' => false]);

        foreach ($companies as $company) {
            $company->setIsApproved(true); // Marque chaque entreprise comme approuvée
        }

        $entityManager->flush();

        // Ajoute un message flash de succès
        $this->addFlash('success', 'Toutes les entreprises ont été approuvées avec succès.');

        // Redirige vers la liste des entreprises
        return $this->redirectToRoute('app_company_index');
    }

    #[Route('/export/pdf', name: 'app_company_export_pdf', methods: ['GET'])]
    public function exportPdf(CompanyRepository $companyRepository): Response
    {
        $companies = $companyRepository->findAll();

        // Configure Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        // Générer le contenu HTML pour le PDF
        $html = $this->renderView('company/export_pdf.html.twig', [
            'companies' => $companies,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Retourner le PDF en réponse
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="entreprises.pdf"',
        ]);
    }

    #[Route('/export/excel', name: 'app_company_export_excel', methods: ['GET'])]
    public function exportExcel(CompanyRepository $companyRepository): Response
    {
        $companies = $companyRepository->findAll();

        // Créer un nouveau fichier Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Entreprises');

        // Ajouter les en-têtes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Adresse');
        $sheet->setCellValue('D1', 'Téléphone');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'Secteur');

        // Ajouter les données
        $row = 2;
        foreach ($companies as $company) {
            $sheet->setCellValue('A' . $row, $company->getId());
            $sheet->setCellValue('B' . $row, $company->getName());
            $sheet->setCellValue('C' . $row, $company->getStreet() . ', ' . $company->getZipcode() . ' ' . $company->getCity());
            $sheet->setCellValue('D' . $row, $company->getPhone());
            $sheet->setCellValue('E' . $row, $company->getEmail());
            $sheet->setCellValue('F' . $row, $company->getSector()->getName());
            $row++;
        }

        // Écrire le fichier Excel
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'companies') . '.xlsx';
        $writer->save($tempFile);

        // Retourner le fichier Excel en réponse
        return new Response(file_get_contents($tempFile), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="entreprises.xlsx"',
        ]);
    }
}
