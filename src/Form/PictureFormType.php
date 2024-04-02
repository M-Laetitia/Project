<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PictureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

          ->add('picture', FileType::class, [
                'label' => false,
                'required' => true, 
                'property_path' => 'path', // The "picture" field in the form is linked to the "path" property of the Picture entity.
                'invalid_message' => 'The image must have a maximum width of 1800 and a minimum of 1600, a maximum height of 1000 and a minimum of 600, its size must not exceed 5M, and the accepted formats are png, jpeg, jpg, and webp.',
                'constraints' => [
                    new Image([
                        // 'maxSize' => '5M', 
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

            ->add('altDescription', TextType::class, [
                'label' => 'Little description : ',
                'required' => true, 
                'invalid_message' => 'The description must not exceed 125 characters.',
                'constraints' => [
                    new Length([
                        'max' => 125,
                        
                    ]),
                ],
            ])

            ->add('title', TextType::class, [
                'label' => 'Title : ',
                'required' => false, 
                'invalid_message' => 'The title must be between 5 and 30 characters.',
                'constraints' => [
                    new Length([
                        'min' => 5, 
                        'max' => 30, 
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}


