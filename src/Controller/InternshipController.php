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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/internship')]
final class InternshipController extends AbstractController
{
    #[Route('/', name: 'app_internship_index', methods: ['GET'])]
    public function index(Request $request, InternshipRepository $internshipRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Récupérer le terme de recherche
        $searchTerm = $request->query->get('search', '');

        // Rechercher les stages correspondant au terme
        $queryBuilder = $internshipRepository->createQueryBuilder('i');
        if (!empty($searchTerm)) {
            $queryBuilder->where('i.title LIKE :searchTerm OR i.description LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Pagination
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Query
            $request->query->getInt('page', 1), // Numéro de la page
            5 // Nombre d'éléments par page
        );

        return $this->render('internship/index.html.twig', [
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
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

    #[Route('/internship/{id}', name: 'app_internship_show', methods: ['POST', 'GET'])]
    public function show(Internship $internship): Response
    {
        return $this->render('internship/show.html.twig', [
            'internship' => $internship,
            'reports' => $internship->getReports(), // Passez les rapports au template
        ]);
    }

    #[Route('/{id}/edit', name: 'app_internship_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Internship $internship, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER'); // Vérifie que l'utilisateur a le rôle 'ROLE_TEACHER'

        $form = $this->createForm(InternshipType::class, $internship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_internship_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('internship/edit.html.twig', [
            'internship' => $internship,
            'form' => $form->createView(), // Assurez-vous d'utiliser createView() pour passer le formulaire à Twig
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

    #[Route('/internship/export/pdf', name: 'app_internship_export_pdf', methods: ['GET'])]
    public function exportPdf(InternshipRepository $internshipRepository): Response
    {
        $internships = $internshipRepository->findAll();

        // Configure Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        // Générer le contenu HTML pour le PDF
        $html = $this->renderView('internship/export_pdf.html.twig', [
            'internships' => $internships,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Retourner le PDF en réponse
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="stages.pdf"',
        ]);
    }

    #[Route('/internship/export/excel', name: 'app_internship_export_excel', methods: ['GET'])]
    public function exportExcel(InternshipRepository $internshipRepository): Response
    {
        $internships = $internshipRepository->findAll();

        // Créer un nouveau fichier Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Stages');

        // Ajouter les en-têtes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Titre');
        $sheet->setCellValue('C1', 'Description');
        $sheet->setCellValue('D1', 'Date de début');
        $sheet->setCellValue('E1', 'Date de fin');

        // Ajouter les données
        $row = 2;
        foreach ($internships as $internship) {
            $sheet->setCellValue('A' . $row, $internship->getId());
            $sheet->setCellValue('B' . $row, $internship->getTitle());
            $sheet->setCellValue('C' . $row, $internship->getDescription());
            $sheet->setCellValue('D' . $row, $internship->getStartDate() ? $internship->getStartDate()->format('d/m/Y') : 'Non défini');
            $sheet->setCellValue('E' . $row, $internship->getEndDate() ? $internship->getEndDate()->format('d/m/Y') : 'Non défini');
            $row++;
        }

        // Écrire le fichier Excel
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'internships') . '.xlsx';
        $writer->save($tempFile);

        // Retourner le fichier Excel en réponse
        return new Response(file_get_contents($tempFile), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="stages.xlsx"',
        ]);
    }

    #[Route('/pending', name: 'app_internship_pending', methods: ['GET'])]
    public function pending(InternshipRepository $internshipRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $internships = $internshipRepository->findBy(['isPending' => true]);

        return $this->render('internship/pending.html.twig', [
            'internships' => $internships,
        ]);
    }

    #[Route('/internship/{id}/approve', name: 'app_internship_approve', methods: ['POST'])]
    public function approve(Request $request, Internship $internship, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER'); // Vérifie que l'utilisateur a le rôle 'ROLE_TEACHER'

        // Vérifie la validité du jeton CSRF
        if ($this->isCsrfTokenValid('approve' . $internship->getId(), $request->request->get('_token'))) {
            // Met à jour la propriété isPending
            $internship->setPending(false);
            $entityManager->flush();

            // Ajoute un message flash de succès
            $this->addFlash('success', 'Le stage a été approuvé avec succès.');
        } else {
            // Ajoute un message flash d'erreur si le jeton CSRF est invalide
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        // Redirige vers la liste des stages en attente
        return $this->redirectToRoute('app_internship_pending');
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