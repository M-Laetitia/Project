<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artistName', TextType::class)
            ->add('artistDiscipline', TextType::class)

            ->add('pictures', FileType::class, [
                'label' => false,
                'mapped' => false, // Ne pas mapper ce champ à une propriété d'entité
                'required' => false,
            ])

            ->add('altDescription', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'The  description cannot be longer than {{ limit }} characters.',
                    ]),
                ],
                'mapped' => false, // Ne pas mapper ce champ à une propriété d'entité
            ])

        // JSON ARTIST-INFO
            ->add('emailPro', EmailType::class, [
                'label' => 'Email Professionnel',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('discipline', TextType::class, [
                'label' => 'Discipline',
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
            'allow_extra_fields' => true, // Permettre les champs supplémentaires qui ne sont pas directement liés à l'entité User
        ]);
    }
}
