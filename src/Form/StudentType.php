<?php

namespace App\Form;

use App\Entity\Grade;
use App\Entity\School;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('address')

            ->add('zipcode')
            ->add('town')
            ->add('school', EntityType::class, [
                'class' => School::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('grade', EntityType::class, [
                'class' => Grade::class,
                'choice_label' => 'name', // Affiche les noms des grades
                'multiple' => false,      // Choix unique
                'expanded' => false,      // Liste déroulante (radio si true)
                'placeholder' => 'Choisir une classe', // Option vide par défaut
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
