<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Cookie;

class LoginController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Get the current user if authenticated
        $user = $this->getUser();
        if ($user) {
            $response = $this->render('login/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);

            // Set cookies with user information
            $response->headers->setCookie(new Cookie('user_firstname', $user->getFirstName(), strtotime('+30 days')));
            $response->headers->setCookie(new Cookie('user_lastname', $user->getLastName(), strtotime('+30 days')));

            return $response;
        }

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        // Clear the cookies
        $response = new Response();
        $response->headers->clearCookie('user_firstname');
        $response->headers->clearCookie('user_lastname');
        
        return $response;
    }
}
