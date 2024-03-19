<?php

namespace App\Form;

use App\Entity\Workshop;
use App\Form\ProgrammeType;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class WorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,  array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name : ',
                'required' => true, 
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description :  ',
                'required' => true, 
                'constraints' => [
                    new Length([
                        'min' => 150,
                        'max' => 250,
                        'minMessage' => 'The text must contain at least {{ limit }} characters.',
                        'maxMessage' => 'The text cannot exceed {{ limit }} characters.',
                    ]),
                ],

            ])
            ->add('detail', TextareaType::class, [
                'label' => 'Detail :  ',
                'required' => true, 
                'constraints' => [
                    new Length([
                        'min' => 400,
                        'minMessage' => 'The text must contain at least {{ limit }} characters.',
                    ]),
                ],
            ])

            ->add('quote', TextType::class, [
                'label' => 'Quote : ',
                'required' => true, 
            ])


            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Starting Date: * ',
                'required' => true, 
                // 'constraints' => [
                //     new Date([
                //         'message' => 'The start date should be a valid date.',
                //     ]),
                //     new GreaterThanOrEqual([
                //         'value' => new \DateTime('today'),
                //         'message' => 'The start date should be today or later.',
                //     ]),
                // ],
                
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text', 
                'label' => 'Ending Date: * ',
                'required' => true, 
                // 'constraints' => [
                //     new Date([
                //         'message' => 'The end date should be a valid date.',
                //     ]),
                //     new GreaterThan([
                //         'propertyPath' => 'startDate',
                //         'message' => 'The end date should be later than the start date.',
                //     ]),
                // ],


            ])
            ->add('nbRooms', IntegerType::class, [
                'label' => 'Capacity : ',
                'required' => true, 
                'attr' => ['min' => 0],
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'The number must be greater than zero.',
                    ]),
                ],
            ])

            
            ->add('user', EntityType::class, [
                'label' => 'Supervisor: * ',
                'required' => true, 
                'class' => 'App\Entity\User',
                'choice_label' => 'username', 
                'placeholder' => 'Choose a supervisor',
                'choices' => $options['users']
            ])


            ->add('programmes', CollectionType::class, [
                // La collection attend l'élèment qu'elle rentrera dans le form mais ce n'est pas obligatoire que ça soit  un form, ça pourrait être une entité (collectionType) par exemple des user
                'entry_type' => ProgrammeType::class, 
                'prototype' => true,  // autoriser les ajouts en JS car prototype c'est des objets en JS
                // autoriser l'ajout de nvx élèments dans l'entité workshop qui seront persistés grâce au cascade persist sur l'élèment programme
                // et cela va activer le Data prototype qui sera un attribut HTML qu'on pourra manipuler en JS
                'allow_add' => true, // permettre d'ajouter plusieurs programmes
                'allow_delete' => true, // permettre de supprimer des élèments
                'by_reference' =>false, // il est obligatoire car Workshop n'a pas de set programme mais c'est Programme qui contient set session
                // C'est Programme qui est propriétaire de la relation pour éviter un maping false on est obligé de rajouter un by_reference false
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
            'data_class' => Workshop::class,
            'users' => [] // définir un tableau vide pour éviter error
        ]);

    }
}

