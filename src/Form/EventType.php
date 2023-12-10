<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\AreaCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)

            ->add('status', ChoiceType::class, [
                'choices' => [
                    'open' => 'open',
                    'close' => 'close',
                    'open soon' => 'open_soon',
                    'archived' => 'archived',
                ],
                'multiple' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'private' => 'private',
                    'public' => 'public',
                ],
                'multiple' => false,
            ])


            ->add('nbRooms', IntegerType::class, [
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'The number of place must be a number greater than zero.'
                    ]),
                ],
                'attr' => ['min' => 0],
            ])

            
            ->add('areaCategories', EntityType::class, [
                'class' => AreaCategory::class,
                'multiple' => true,
                'expanded' => true, 
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
