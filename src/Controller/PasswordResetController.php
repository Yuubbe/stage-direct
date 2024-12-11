<?php
// src/Controller/PasswordResetController.php
namespace App\Controller;

use App\Repository\UserRepository;
use App\Form\ForgotPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordResetController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function request(Request $request, UserRepository $userRepository, MailerInterface $mailer, UserPasswordHasherInterface $passwordHasher)
    {
        // Création du formulaire pour demander la réinitialisation du mot de passe
        $form = $this->createForm(ForgotPasswordRequestFormType::class);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'email de l'utilisateur
            $email = $form->getData()['email'];

            // Vérifier si un utilisateur existe avec cet email
            $user = $userRepository->findOneByEmail($email);
            if (!$user) {
                // Si l'utilisateur n'existe pas
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
                return $this->redirectToRoute('app_forgot_password');
            }

            // Générer un mot de passe aléatoire
            $newPassword = bin2hex(random_bytes(8)); // Générer un mot de passe aléatoire de 16 caractères
            $encodedPassword = $passwordHasher->hashPassword($user, $newPassword); // Hacher le mot de passe

            // Mettre à jour le mot de passe de l'utilisateur dans la base de données
            $user->setPassword($encodedPassword);
            $userRepository->save($user); // Sauvegarder l'utilisateur avec le mot de passe mis à jour

            // Créer un email pour envoyer le nouveau mot de passe à l'utilisateur
            $emailMessage = (new Email())
                ->from('gamblinvincent@ik.me') // Remplacez par une adresse valide
                ->to($user->getEmail()) // Destinataire
                ->subject('Votre mot de passe a été réinitialisé')
                ->text("Voici votre nouveau mot de passe : $newPassword"); // Contenu du message

            // Envoyer l'email
            $mailer->send($emailMessage);

            // Ajouter un message de succès pour l'utilisateur
            $this->addFlash('success', 'Un nouveau mot de passe a été envoyé à votre email.');
            return $this->redirectToRoute('app_login');
        }

        // Rendu du formulaire
        return $this->render('password_reset/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
