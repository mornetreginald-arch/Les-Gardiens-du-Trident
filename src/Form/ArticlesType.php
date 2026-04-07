<?php

// Namespace de la classe (organisation du projet)
namespace App\Form;

// Import des entités utilisées
use App\Entity\Articles;
use App\Entity\Categorie;
use App\Entity\Commande;

// Types de champs Symfony
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

// Interfaces nécessaires
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Classe du formulaire pour l'entité Articles
class ArticlesType extends AbstractType
{
    // Méthode qui construit le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // Champ nom du produit (string simple)
            ->add('nom_produit')
            // Champ prix avec type Money (format monétaire)
            ->add('prix', MoneyType::class, [
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('stock')

            // Champ upload image
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                // Contraintes de validation
                'constraints' => [
                    new File(
                        maxSize: '2M',
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        mimeTypesMessage: 'Upload une image valide',
                    )
                ],
            ])

            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Lie le formulaire à l'entité Articles
            'data_class' => Articles::class,
        ]);
    }
}
