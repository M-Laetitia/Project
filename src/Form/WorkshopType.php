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
                'label' => 'Name of the Workshop: * ',
                'required' => true, 
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Write a little description: * ',
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
                'label' => 'Write a complete and detailed description: * ',
                'required' => true, 
                'constraints' => [
                    new Length([
                        'min' => 400,
                        'minMessage' => 'The text must contain at least {{ limit }} characters.',
                    ]),
                ],
            ])

            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Starting Date: * ',
                'required' => true, 
                'constraints' => [
                    new Date([
                        'message' => 'The start date should be a valid date.',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => new \DateTime('today'),
                        'message' => 'The start date should be today or later.',
                    ]),
                ],
                
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text', 
                'label' => 'Ending Date: * ',
                'required' => true, 
                'constraints' => [
                    new Date([
                        'message' => 'The end date should be a valid date.',
                    ]),
                    new GreaterThan([
                        'propertyPath' => 'startDate',
                        'message' => 'The end date should be later than the start date.',
                    ]),
                ],


            ])
            ->add('nbRooms', IntegerType::class, [
                'label' => 'Capacity: * ',
                'required' => true, 
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'The number must be greater than zero.',
                    ]),
                ],
            ])

            //! ajouter les contraintes pour l'image, format, taille, poids
            ->add('picture', FileType::class, [
                'label' => 'Add a banner: ',
                'required' => false,
            ])
            ->add('user', EntityType::class, [
                'label' => 'Supervisor: * ',
                'required' => true, 
                'class' => 'App\Entity\User',
                'choice_label' => 'username', 
                'placeholder' => 'Choose a supervisor',
                'choices' => $options['users']
                // 'query_builder' => $this->$userRepository->findUsersbyRole("ROLE_SUPERVISOR");
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

            ->add('Create', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workshop::class,
            'users' => [] // définir un tableau vide pour éviter error
        ]);

        // $resolver->setRequired('users'); // Declare the "users" option as required
        // $resolver->setAllowedTypes('users', 'array'); // Ensure "users" is of type arra
    }
}

