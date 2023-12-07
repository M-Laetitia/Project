<?php

namespace App\Form;

use App\Entity\AreaParticipation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            // ->add('lastname')
            // ->add('startDate')
            ->add('endDate')
            // ->add('inscriptionDate')
            // ->add('area')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AreaParticipation::class,
        ]);
    }
}
