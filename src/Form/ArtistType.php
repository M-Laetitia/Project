<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ContactType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            // ->add('pictures', FileType::class, [
            //     'label' => false,
            //     'mapped' => false, // Ne pas mapper ce champ à une propriété d'entité
            //     // 'mapped' => !$options['edit_mode'], // Ne mappez pas le champ si c'est en mode édition
            //     'required' => false, 
            // ])

            // ->add('altDescription', TextType::class, [
            //     'constraints' => [
            //         new Length([
            //             'max' => 150,
            //             'maxMessage' => 'The  description cannot be longer than {{ limit }} characters.',
            //         ]),
            //     ],
            //     'mapped' => false, // Ne pas mapper ce champ à une propriété d'entité
            //     'required' => false, 
            //     // 'label'=> 'sdsddmffds',
            // ])

            // Contact (social networks)

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

            ->add('artistName', TextType::class, [
                'label' => 'artist name',
                'mapped' => false,
                'required' => false, 
                
            ])

            ->add('instagram', UrlType::class, [
                'label' => 'Instagram',
                'mapped' => false,
                'required' => false, 
                
            ])

            ->add('behance', UrlType::class, [
                'label' => 'Behance',
                'mapped' => false,
                'required' => false, 
                
            ])

            // ->add('contacts', CollectionType::class, [
            //     'entry_type' => TextType::class,
            //     'entry_options' => [
            //         'label' => false,
            //         // Autres options pour chaque champ de contact
            //     ],
            //     'mapped' => false,
            //     'required' => false,
            //     'allow_add' => true, // Permettre l'ajout dynamique de nouveaux champs
            // ])


            // ->add('Submit', SubmitType::class)
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true, // Permettre les champs supplémentaires qui ne sont pas directement liés à l'entité User
            'editMode' => false,
        ]);
    }
}
