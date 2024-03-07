<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    // Honey pot: add two fieds : the email and the honeypot
    // use const to define the names of the fields. Switch the names we would typically use for those two fields
    public const EMAIL_FIELD_NAME ='information';
    public const HONEYPOT_FIELD_NAME = 'email';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::EMAIL_FIELD_NAME, EmailType::class, [
                'label' =>'e-mail',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => 'strict'])
                ],
                'attr' => [
                    'placeholder' => 'Enter your e-mail address'
                ]
            ])
            ->add(self::HONEYPOT_FIELD_NAME, TextType::class, [
                'required' => false,
                'label' => false, // Disables the display of the label
                'attr' => ['hidden' => true], // Makes the field invisible
            ])

            ->add('username' , TextType::class, [
                'label' =>'usermane',
                'attr' => [
                    'placeholder' => 'Enter your username'
                ]
            ])


            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                // 'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirmation'],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]), 
                    // new Regex([
                    //     'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{12,}$/',
                    //     'message' => 'Your password must be at least 12 characters long, contain at least one number, one uppercase and one lowercase letter, and one special character.',
                    // ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

            
        ;
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => User::class,
    //     ]);
    // }
}
