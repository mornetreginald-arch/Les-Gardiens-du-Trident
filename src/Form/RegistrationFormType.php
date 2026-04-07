<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

// Classe du formulaire d'inscription
class RegistrationFormType extends AbstractType
{
    // Construction du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => [
                'placeholder' => 'exemple@email.com'
                ]
            ])
            
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                'placeholder' => 'Votre nom'
                ],
            
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                'placeholder' => 'Votre Prenom'
                ],
        
            ])
            ->add('telephone', TelType::class, [
                'attr' => [
                    'placeholder' => '+33 6 12 34 56 78'
                ]
            ])

            ->add('rue', null, [
                'attr' => [
                'placeholder' => '123 Rue de le République'
                ]
            ])
            ->add('code_postal', null, [
                'attr' => [
                'placeholder' => '75001'
                ]
            ])
            ->add('ville', null, [
                'attr' => [
                'placeholder' => 'Paris'
                ]
            ])
            ->add('pays', null, [
                'attr' => [
                'placeholder' => 'France'
                ]
            ])
            // Champ mot de passe avec confirmation
            ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            // Message si les deux champs ne correspondent pas
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
            'required' => true,

            'first_options'  => [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Entrez votre mot de passe',
                    'autocomplete' => 'new-password',
                ],
                'constraints' => [
            ],
            ],

            'second_options' => [
                'label' => 'Confirmer le mot de passe',
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe',
                    'autocomplete' => 'new-password',
                    ],
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
    'mapped' => false,
    'constraints' => [
        new IsTrue([
            'message' => 'Vous devez accepter les conditions.',
        ]),
    ],
]);
}

    // Configuration du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Lie le formulaire à l'entité User
            'data_class' => User::class,
        ]);
    }
}
