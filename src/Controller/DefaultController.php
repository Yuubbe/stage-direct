<?php

namespace App\Controller;

use App\Service\MessageGenerator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


#[Route('/home')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]

    public function index(MessageGenerator $messageGenerator): Response
    {
        $message = $messageGenerator->getRandomMessage();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'message' => $message,
            'result'=>'envoyé'
        ]);
    }

    
}
