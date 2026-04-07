<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    // Construction du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            // Champ relation avec les articles
            ->add('articles', EntityType::class, [
                // Entité liée
                'class' => Articles::class,
                'choice_label' => 'id',
                // Permet de sélectionner plusieurs articles
                'multiple' => true,
                // Champ non obligatoire
                'required' => false,
            ])
        ;
    }

    // Configuration du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Lie le formulaire à l'entité Categorie
            'data_class' => Categorie::class,
        ]);
    }
}
