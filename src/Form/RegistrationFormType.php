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

class RegistrationFormType extends AbstractType
{
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
            //     'constraints' => [
            //     new NotBlank([
            //     'message' => 'Entrez votre nom',
            // ])
            //     ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                'placeholder' => 'Votre Prenom'
                ],
            //     'constraints' => [
            //     new NotBlank([
            //     'message' => 'Entrez votre Prenom',
            // ])
            //     ],
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
    ->add('plainPassword', RepeatedType::class, [
    'type' => PasswordType::class,
    'mapped' => false,
    'invalid_message' => 'Les mots de passe ne correspondent pas.',
    'required' => true,

    'first_options'  => [
        'label' => 'Mot de passe',
        'attr' => [
            'placeholder' => 'Entrez votre mot de passe',
            'autocomplete' => 'new-password',
        ],
        'constraints' => [
            // new NotBlank([
            //     'message' => 'Entrez un mot de passe',
            // ]),
            // new Length([
            //     'min' => 6,
            //     'minMessage' => 'Votre mot de passe doit avoir au moins 6 caractères',
            //     'max' => 4096,
            // ]),
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


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
