<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'] ?? null;
        $existingRoles = $user && method_exists($user, 'getRoles') ? $user->getRoles() : [];

        // Si l'utilisateur n'a pas encore de rôle, on lui donne ROLE_STUDENT par défaut
        if (empty($existingRoles)) {
            $existingRoles = ['ROLE_STUDENT'];
        }

        $builder
            ->add('firstName')
            ->add('lastname')
            ->add('email')
            /*
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                ],
                'invalid_message' => 'The password fields must match.',
            ])
            */
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Student' => 'ROLE_STUDENT',
                    'User' => 'ROLE_USER',
                    'Teacher' => 'ROLE_TEACHER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'data' => $existingRoles,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select at least one role',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
