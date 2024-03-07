<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', TextType::class, [
            'label' => false,
            'required' => false,
            'constraints' => [
                new Length([
                    'min' => 3,
                    'minMessage' => 'You must enter a search of at least 3 characters.',
                ]),
            ],
            'attr' => [
                'placeholder' => 'artist name'
            ],
            
        ])

        ->add('discipline', TextType::class, [
            'label' => false,
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new Length([
                    'min' => 3,
                    'minMessage' => 'You must enter a search of at least 3 characters.',
                ]),
            ],
            'attr' => [
                'placeholder' => 'discipline'
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
