<?php

namespace App\Form;

use App\Entity\Lesson;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('workshop', HiddenType::class)
        ->add('lesson', EntityType::class, [
            'label' => 'Lesson: * ',
            'placeholder' => 'Choose a lesson',
            'class' => Lesson::class,
            'choice_label' =>  'name',
            'required' => true, 
        ])

        ->add('StartDate', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Starting date: * ',
            'required' => true, 
        ])
        ->add('EndDate', DateTimeType::class, [
            'widget' => 'single_text', 
            'label' => 'Ending date: * ',
            'required' => true, 
            // 'attr' => ['min' => 1, 'max' => 100]
        ])

        // ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
