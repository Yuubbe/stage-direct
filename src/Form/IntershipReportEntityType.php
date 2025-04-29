<?php

namespace App\Form;

use App\Entity\InternshipReportEntity; // Assurez-vous que c'est le bon nom
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EmilePerron\TinymceBundle\Form\Type\TinymceType;

class IntershipReportEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('contenu', TinymceType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InternshipReportEntity::class, // Map the form to the entity
        ]);
    }
}
