<?php

namespace App\Form;

use App\Entity\Studio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Studio::class,
        ]);
    }
}
