<?php

namespace App\Form;

use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $categories = [
            'Accouchement',
            'Allergies',
            'Cancer',
            'Cardiologie',
            'Chirurgie',
            'Dermatologie',
            'Endocrinologie',
            'Gastro-entérologie',
            'Gynécologie',
            'Hématologie',
            'Infectiologie',
            'Médecine générale',
            'Neurologie',
            'Ophtalmologie',
            'Orthopédie',
            'Oto-rhino-laryngologie',
            'Pédiatrie',
            'Psychiatrie',
            'Rhumatologie',
            'Urologie'
        ];
        function getCategoryLabel($category) {
            return $category;
        }
        $builder
            ->add('name', ChoiceType::class, [
                'label' => 'Categorie de maladie',
                'choices' => $categories,
                'choice_label' => function ($category) {
                    return $category;
                },
            ])
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Status::class,
        ]);
    }
}
