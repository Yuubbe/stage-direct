<?php
// src/Security/PasswordResetService.php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class PasswordResetService
{
    private $mailer;
    private $router;
    private $userRepository;
    private $entityManager;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function sendPasswordResetEmail(string $email)
    {
        // Get user by email
        $user = $this->userRepository->findOneByEmail($email);

        if ($user) {
            // Generate a reset token and save it to the user
            $token = bin2hex(random_bytes(32)); // Generate a random token
            $user->setResetToken($token);

            // Persist the updated user entity
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Create the reset link
            $resetUrl = $this->router->generate('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            // Send the email
            $emailMessage = (new Email())
                ->from('gamblinvincent@ik.me')
                ->to($user->getEmail())
                ->subject('Password Reset Request')
                ->html("<p>To reset your password, please click <a href=\"$resetUrl\">here</a>.</p>");

            $this->mailer->send($emailMessage);
        }
    }
}
