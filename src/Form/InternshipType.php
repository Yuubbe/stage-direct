<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Internship;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class InternshipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du stage',
                'attr' => [
                    'class' => 'form-control rounded-pill',
                    'placeholder' => 'Entrez le titre du stage',
                    'autocomplete' => 'off',
                ],
                'label_attr' => [
                    'class' => 'form-label fw-bold'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control rounded',
                    'placeholder' => 'Décrivez le stage en détail',
                    'rows' => 5,
                    'style' => 'resize: none;',
                ],
                'label_attr' => [
                    'class' => 'form-label fw-bold'
                ],
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => [
                    'class' => 'form-control rounded-pill',
                ],
                'label_attr' => [
                    'class' => 'form-label fw-bold'
                ],
                'html5' => true,
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'attr' => [
                    'class' => 'form-control rounded-pill',
                ],
                'label_attr' => [
                    'class' => 'form-label fw-bold'
                ],
                'html5' => true,
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'id',
                'label' => 'Entreprise',
                'attr' => [
                    'class' => 'form-select rounded-pill',
                ],
                'label_attr' => [
                    'class' => 'form-label fw-bold'
                ],
                'placeholder' => 'Sélectionnez une entreprise',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Internship::class,
            'attr' => [
                'class' => 'needs-validation',
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}