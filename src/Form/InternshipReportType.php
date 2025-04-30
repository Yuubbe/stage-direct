<?php

namespace App\Form;

use App\Entity\Internship;
use App\Entity\InternshipReport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InternshipReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submissionDate', null, [
                'widget' => 'single_text',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu du rapport',
                'attr' => ['class' => 'form-control tinymce'],
            ])
            ->add('title')
            ->add('internship', EntityType::class, [
                'class' => Internship::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InternshipReport::class,
        ]);
    }
}
