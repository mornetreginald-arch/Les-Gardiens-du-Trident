<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categorie;
use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('id_produit')
            ->add('nom_produit')
            ->add('prix', MoneyType::class, [
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('stock')


            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
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
            // ->add('commande', EntityType::class, [
            //     'class' => Commande::class,
            //     // 'choice_label' => 'id',
            //     'multiple' => true,
            //     'required' => false,
            // ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
