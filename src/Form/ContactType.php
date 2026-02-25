<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Jean Dupont'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'jean.dupont@exemple.com'
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone (optionnel)',
                'required' => false,
                'attr' => [
                    'placeholder' => '+33 6 12 34 56 78'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Écrivez votre message ici...',
                    'rows' => 9,
                    'class' => 'form-control'
                ]
            ])
        ;
    }
}
