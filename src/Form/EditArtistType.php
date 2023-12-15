<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('emailPro', EmailType::class, [
                'label' => 'Email : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('discipline', TextType::class, [
                'label' => 'Discipline : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('artistName', TextType::class, [
                'label' => 'Artist name : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
