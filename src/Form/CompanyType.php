<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Sector;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'attr' => ['class' => 'form-control']
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code postal',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-control']
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('fax', TextType::class, [
                'label' => 'Fax',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('companySize', TextType::class, [
                'label' => 'Taille de l\'entreprise',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('sector', EntityType::class, [
                'class' => Sector::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un secteur',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
        // Supprimez toute référence à sector_repository si elle existe
    }
}
