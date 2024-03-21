<?php

namespace App\Form;

use App\Entity\Studio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Name : ',
            'required' => false, 
        ])

        ->add('title', TextType::class, [
            'label' => 'Title : ',
            'required' => false, 
        ])

        ->add('description', TextareaType::class, [
            'label' => 'Description : ',
            'required' => false, 
        ])

        ->add('detail', TextareaType::class, [
            'label' => 'Detail: ',
            'required' => false, 
        ])


        ->add('nbRooms', IntegerType::class, [
            'constraints' => [
                new GreaterThan([
                    'value' => 0,
                    'message' => 'The number of place must be a number greater than zero.'
                ]),
            ],
            'attr' => ['min' => 0],
            'label' => 'Capacity : ',
            'required' => false, 
        ])

        ->add('equipment', TextType::class, [
            'label' => 'Equipement : ',
            'required' => false, 
            'mapped' => false, 
        ])

        ->add('preview', FileType::class, [
            'label' => false,
            'mapped' => false,
            'required' => false, 
            'property_path' => 'path', // le champ "picture" dans le form est lié à la propriété "path" de l'entité Picture
            'invalid_message' => 'The image must have a maximum width of 1800 and a minimum of 1600, a maximum height of 1000 and a minimum of 600, its size must not exceed 2M, and the accepted formats are png, jpeg, jpg, and webp.',
            'constraints' => [
                new Image([
                    // 'maxSize' => '2M', 
                    // 'maxWidth' => 1800, 
                    // 'maxHeight' => 1000, 

                    // 'minWidth' => 1200, 
                    // 'minHeight' => 600, 

                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'image/webp',
                    ],
                ]),
            ],
        ])

        ->add('titlePreview', TextType::class, [
            'mapped' => false, 
            'label' => 'Preview title : ',
            'required' => false, 
        ])

        ->add('altDescriptionPreview', TextType::class, [
            'mapped' => false, 
            'label' => 'Preview little description  : ',
            'required' => false, 
        ])


        ->add('banner', FileType::class, [
            'label' => false,
            'mapped' => false,
            'required' => false, 
            'property_path' => 'path', // le champ "picture" dans le form est lié à la propriété "path" de l'entité Picture
            'invalid_message' => 'The image must have a maximum width of 1800 and a minimum of 1600, a maximum height of 1000 and a minimum of 600, its size must not exceed 2M, and the accepted formats are png, jpeg, jpg, and webp.',
            'constraints' => [
                new Image([
                    // 'maxSize' => '2M', 
                    // 'maxWidth' => 1800, 
                    // 'maxHeight' => 1000, 

                    // 'minWidth' => 1200, 
                    // 'minHeight' => 600, 

                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'image/webp',
                    ],
                ]),
            ],
        ])

        ->add('titleBanner', TextType::class, [
            'mapped' => false, 
            'label' => 'Banner title : ',
            'required' => false, 
        ])

        ->add('altDescriptionBanner', TextType::class, [
            'mapped' => false, 
            'label' => 'Banner little description : ',
            'required' => false, 
        ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Studio::class,
        ]);
    }
}
