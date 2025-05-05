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

    #[Route('/internship/{id}/approve', name: 'app_internship_approve', methods: ['POST'])]
    public function approve(Request $request, Internship $internship, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        if ($this->isCsrfTokenValid('approve' . $internship->getId(), $request->request->get('_token'))) {
            $internship->setIsPending(false);
            $entityManager->flush();

            $this->addFlash('success', 'Le stage a été approuvé avec succès.');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

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