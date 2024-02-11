<?php

namespace App\Form;

use App\Entity\Studio;
use App\Entity\Timeslot;
use App\Entity\TimeSlotAvailability;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TimeSlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('day', DateType::class, [
                'label' => 'Date',
                'mapped' => false,
                'required' => true, 
                'label' => 'Day: ',
            ])


            ->add('studio', EntityType::class, [
                'required' => true, 
                'class' => Studio::class,
            ])

            ->add('TimeSlotAvailability', EntityType::class, [
                'required' => true, 
                'class' => TimeSlotAvailability::class,
            ])


            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Timeslot::class,
        ]);
    }
}

