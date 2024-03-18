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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('emailPro', EmailType::class, [
                'label' => 'Contact (email) : ',
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

            ->add('instagram', UrlType::class, [
                'label' => 'Instagram : ',
                'mapped' => false,
                'required' => false, 
                
            ])

            ->add('behance', UrlType::class, [
                'label' => 'Behance : ',
                'mapped' => false,
                'required' => false, 
                
            ])

            ->add('twitter', UrlType::class, [
                'label' => 'Twitter : ',
                'mapped' => false,
                'required' => false, 
                
            ])

            ->add('dribbble', UrlType::class, [
                'label' => 'Dribbble : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'mapped' => false,
                // 'expanded' => true,
                // 'multiple' => false,
                'required' => true, 
                'choices' => [
                    'Choose a category' => '',
                    'Illustration' => 'illustration',
                    'Photography' => 'photography',
                    'Graphism' => 'graphism',
                    'Craftsman' => 'craftsman',
                    'Other' => 'Other',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    // Applique une classe 'option-class' à toutes les options, y compris celle désactivée
                    $attrs = ['class' => 'option'];
                    if ($value === '') {
                        // Vous pouvez ajouter ici d'autres attributs spécifiques pour l'option désactivée si nécessaire
                        $attrs['disabled'] = '';
                    }
                    return $attrs;
                },
            ])

            ->add('bio', TextareaType::class, [
                'label' => 'Biography : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('quote', TextType::class, [
                'label' => 'Quote : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('website', UrlType::class, [
                'label' => 'Website : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('shop', TextType::class, [
                'label' => 'Shop / gallery Name : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('country', TextType::class, [
                'label' => 'Country : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('street', TextType::class, [
                'label' => 'Street : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('city', TextType::class, [
                'label' => 'City : ',
                'mapped' => false,
                'required' => false, 
            ])

            ->add('postalCode', TextType::class, [
                'label' => 'Postal Code : ',
                'mapped' => false,
                'required' => false, 
            ])

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
