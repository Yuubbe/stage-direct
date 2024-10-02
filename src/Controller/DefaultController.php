<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


#[Route('/')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'result'=>'envoyé'
        ]);
    }

    #[Route('/sendmail/', name: 'app_sendmail')]
    public function sendmail(): Response
    {
        $result = '';
        try {

            $email = (new Email())
            ->from('gamblinvincent@ik.me')
            ->to('gamblinvincent50@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Verification')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
            $result = "Mail envoyé";

        $mailer->send($email);
           
        } catch (\Throwable $th) {
            //throw $th;
            $result = $th->getMessage();
        }
        return $this->render('default/index.html.twig', [
            'result' => 'OK',
        ]);
    }
}
