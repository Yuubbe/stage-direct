<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Sector;
use App\Repository\SectorRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sectorRepository = $options['sector_repository'];
        $sectors = $sectorRepository->findAll();
        $sectorChoices = [];
        foreach ($sectors as $sector) {
            $sectorChoices[$sector->getName()] = $sector->getId();
        }

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
            ->add('sector', ChoiceType::class, [
                'label' => 'Secteur',
                'required' => false,
                'choices' => $sectorChoices,
                'attr' => ['class' => 'form-control'],
                'choice_label' => function ($choice, $key, $value) use ($sectorRepository) {
                    return $sectorRepository->find($value)->getName();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);

        $resolver->setRequired([
            'sector_repository'
        ]);

        $resolver->setAllowedTypes('sector_repository', SectorRepository::class);
    }
}
