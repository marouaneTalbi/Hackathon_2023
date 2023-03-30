<?php

namespace App\Form;

use App\Entity\Content;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('images', FileType::class,[
                     'multiple' => true,
                     'mapped' => false,
            ])
            ->add('videos', FileType::class,[
                'multiple' => true,
                'mapped' => false,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de publication',
                'choices' => [
                    'Tuto' => 'Tuto',
                    'Guide' => 'Guide',
                    'Article' => 'Article',
                ],
                'placeholder' => 'Type de publication',
            ])
            /*->add('tags', ChoiceType::class, [
                'label' => 'Tags',
                'choices' => [
                    'tag 1' => 'tag 1',
                    'tag 2' => 'tag 2',
                    'tag 3' => 'tag 3',
                ],
                'placeholder' => 'Tags',
            ])*/

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
