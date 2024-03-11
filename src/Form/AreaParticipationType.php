<?php

namespace App\Form;

use App\Entity\AreaParticipation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AreaParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'required' => true, 
                'attr' => [
                    'placeholder' => 'Enter your first name'
                ]
            ])

            ->add('lastname', TextType::class, [
                'label' => 'Last name',
                'required' => true, 
                'attr' => [
                    'placeholder' => 'Enter your last name'
                ]
            ])
            
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AreaParticipation::class,
        ]);
    }
}
