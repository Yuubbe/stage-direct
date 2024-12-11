<?php
// src/Form/ForgotPasswordRequestFormType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;

class ForgotPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse e-mail',
                'constraints' => [
                    new Email(['message' => 'Veuillez entrer une adresse e-mail valide.']),
                ],
            ]);
    }
}
