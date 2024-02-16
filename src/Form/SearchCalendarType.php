<?php

namespace App\Form;

use App\Entity\Area;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchCalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('name')
            // ->add('description')
            // ->add('detail')
            // ->add('startDate')
            // ->add('endDate')
            // ->add('nbRooms')
            ->add('type', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Search by type',
                'placeholder' => 'Select a type',
                'choices' => [
                    'Event' => 'event',
                    'Exposition' => 'exposition',
                    'Workshop' => 'workshop',
                ]
            ])

            ->add ('filter', SubmitType::class)

            // ->add('status')
            // ->add('slug')
            // ->add('areaCategories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Area::class,
        ]);
    }
}
