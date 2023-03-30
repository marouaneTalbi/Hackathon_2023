<?php

namespace App\Form;

use App\Entity\Content;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'required' => false,
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

           ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'query_builder' => function (TagRepository $tagRepository) {
                   return $tagRepository->createQueryBuilder('t')
                       ->orderBy('t.name', 'ASC');
                },
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
