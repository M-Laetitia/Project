<?php

namespace App\Form;

use App\Entity\Studio;
use App\Entity\Timeslot;
use App\Entity\TimeSlotAvailability;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TimeSlotType extends AbstractType
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // ->add('day', DateType::class, [
            //     'mapped' => false,
            //     'required' => true, 
            //     'widget' => 'single_text',
            //     // 'label' => 'Day: ',
                
            // ])

            // ->add('studio', EntityType::class, [
            //     'required' => true, 
            //     'class' => Studio::class,

                
            // ])

            ->add('TimeSlotAvailability', EntityType::class, [
                'required' => true, 
                'class' => TimeSlotAvailability::class,
                'choices' => $this->getTimeSlots($options['studioId'], $options['selectedDate']),

            ])

            ->add('Submit', SubmitType::class)
        ;
    }

    private function getTimeSlots($studioId, $selectedDate)
    {
        // Récupérer les créneaux horaires disponibles en fonction des paramètres
        // Utilisez vos DQL pour cela, en fonction de $studioId et $selectedDate
        
        // Exemple fictif
        $timeSlots = $this->entityManager->getRepository(TimeSlotAvailability::class)
            ->findAvailableTimeSlots($studioId, $selectedDate);
    
        // Formater les créneaux horaires pour les utiliser dans le formulaire
        $choices = [];
        foreach ($timeSlots as $timeSlot) {
            $choices[$timeSlot->getId()] = $timeSlot; 
        }
        
    
        return $choices;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Timeslot::class,
            'studioId' => null,
            'selectedDate' => null,
        ]);
    }
}

