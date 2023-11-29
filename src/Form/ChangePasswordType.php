<?php

namespace App\Form;

use App\Entity\User;
use App\DTO\ChangePasswordModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('oldPassword', PasswordType::class, [
            
            'label' => 'Current Password',
            'constraints' => new UserPassword([
                'message' => 'The current password is incorrect.',
            ]),
            'attr' => ['autocomplete' => 'current-password'],
        ])
        ->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The two password fields must match exactly.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options' => ['label' => 'New Password'],
            'second_options' => ['label' => 'Repeat New Password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a new password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
                
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Change password',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordModel::class,
        ]);
    }
}
