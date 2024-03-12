<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArtistStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             // JSON ARTIST-INFO
             ->add('emailPro', EmailType::class, [
                'label' => 'Professionnal Email',
                'mapped' => false,
                'required' => true, 
                'attr' => [
                    'placeholder' => 'Enter your e-mail address'
                ]
            ])

            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'mapped' => false,
                'required' => true, 
                'choices' => [
                    'Choose a category' => '',
                    'Illustration' => 'illustration',
                    'Photography' => 'photgraphy',
                    'Graphism' => 'graphism',
                    'Craftsman' => 'craftsman',
                    'Other' => 'Other',
                ],
                'choice_attr' => ['Choose a category' => ['disabled'=>'']]
            ])

            ->add('discipline', TextType::class, [
                'label' => 'Discipline',
                'mapped' => false,
                'required' => true, 
                'attr' => [
                    'placeholder' => 'example: Wood craft Artisan'
                ]
            ])

            ->add('artistName', TextType::class, [
                'label' => 'artist name',
                'mapped' => false,
                'required' => true, 
                'attr' => [
                    'placeholder' => 'Enter your Artist name'
                ]
            ])

            ->add('submit', SubmitType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
