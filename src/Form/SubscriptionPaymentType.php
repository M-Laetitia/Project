<?php

namespace App\Form;

use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SubscriptionPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // ->add('stripePaymentIntent', HiddenType::class)

            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'mapped' => false,
                'required' => true, 
            ])

            ->add('lastname', TextType::class, [
                'label' => 'lastname',
                'mapped' => false,
                'required' => true, 
            ])

            ->add('address', TextType::class, [
                'label' => 'address',
                'mapped' => false,
                'required' => true, 
            ])

            // ->add('stripeToken', TextType::class, [
            //     'mapped' => false, // Ne pas mapper ce champ à l'objet, car il ne fait pas partie de l'entité Subscription
            //     'required' => false, // Ne pas rendre ce champ obligatoire, car il sera rempli côté client via Stripe.js
            // ])



            ->add('Subscribe', SubmitType::class, [
                'attr' => ['data-secret' => '', 'id' => '']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}
