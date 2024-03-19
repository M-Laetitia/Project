<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\AreaCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExpositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name : ',
                'required' => true, 
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description : ',
                'required' => true, 
            ])

            ->add('detail', TextareaType::class, [
                'label' => 'Detail: ',
                'required' => true, 
            ])

            ->add('quote', TextType::class, [
                'label' => 'Quote : ',
                'required' => true, 
            ])

            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Starting date :  ',
                'required' => true, 
            ])

            ->add('endDate',DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Ending date :  ',
                'required' => true, 
            ])

           
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'OPEN' => 'OPEN',
                    'CLOSED' => 'CLOSED',
                    'PENDING' => 'PENDING',
                    'ARCHIVED' => 'ARCHIVED',
                ],
                'multiple' => false,
                'label' => 'Status : ',
                'required' => true, 
            ])

            ->add('type', ChoiceType::class, [
                'choices' => [
                    'private' => 'private',
                    'public' => 'public',
                ],
                'multiple' => false,
                'label' => 'Type : ',
                'required' => true, 
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
                'required' => true, 
            ])

            ->add('areaCategories', EntityType::class, [
                'class' => AreaCategory::class,
                'multiple' => true,
                'expanded' => true, 
                'label' => 'Category : ',
                'required' => true, 
            ])

            ->add('preview', FileType::class, [
                'label' => false,
                'mapped' => false,
                // 'required' => true, 
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
                // 'required' => true, 
            ])

            ->add('altDescriptionPreview', TextType::class, [
                'mapped' => false, 
                'label' => 'Preview little description  : ',
                // 'required' => true, 
            ])


            ->add('banner', FileType::class, [
                'label' => false,
                'mapped' => false,
                // 'required' => true, 
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
                // 'required' => true, 
            ])

            ->add('altDescriptionBanner', TextType::class, [
                'mapped' => false, 
                'label' => 'Banner little description : ',
                // 'required' => true, 
            ])


            ->add('Create', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Area::class,
        ]);
    }
}
