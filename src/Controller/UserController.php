<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface; // Ajouter pour vérifier les rôles

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/{page<\d+>?1}', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, int $page = 1): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Configuration de la pagination
        $itemsPerPage = 10;
        $totalItems = $userRepository->count([]);
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        // Calculer les limites de la page
        $start = ($page - 1) * $itemsPerPage;
        $end = $start + $itemsPerPage - 1;
        
        // Récupérer les utilisateurs pour cette page
        $users = $userRepository->createQueryBuilder('u')
            ->setFirstResult($start)
            ->setMaxResults($itemsPerPage)
            ->orderBy('u.lastName', 'ASC')
            ->addOrderBy('u.firstName', 'ASC')
            ->getQuery()
            ->getResult();

        // Générer les pages à afficher (5 pages autour de la page courante)
        $pagesToShow = [];
        $minPage = max(1, $page - 2);
        $maxPage = min($totalPages, $page + 2);
        
        for ($i = $minPage; $i <= $maxPage; $i++) {
            $pagesToShow[] = $i;
        }

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
                'hasPreviousPage' => $page > 1,
                'previousPage' => $page > 1 ? $page - 1 : null,
                'hasNextPage' => $page < $totalPages,
                'nextPage' => $page < $totalPages ? $page + 1 : null,
                'pages' => $pagesToShow
            ]
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
