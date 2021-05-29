<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AjouterProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Femme' => 'femme',
                    'Homme' => 'homme',
                    'Enfant' => 'enfant',
                ],])
            ->add('couleur',  ChoiceType::class, [
                'choices' => [
                    'Noir' => 1,
                    'Blanc' => 2,
                    'Rouge' => 3,
                    'Vert' => 4,
                    'Jaune' => 5,
                    'Bleu' => 6,
                    'Rose' => 7,
                    'Marron' => 8,
                ],
            ])
            ->add('sexe', ChoiceType::class, ["attr"=>['class' => 'choixSexe'], 
                'choices' => [
                    'Feminin' => 'f',
                    'Masculin' => 'm',
                ],             
                'label' => 'Sexe',
                'expanded' => true,
                'multiple' => false])
            ->add('titre')
            ->add("photo", FileType::class, ["mapped" => false ])
            ->add('description')
            ->add('stock')
            ->add('prix', NumberType::class, ["attr"=>['class' => 'prixHt'],
                'label' => 'Prix Hors Taxe',
                'scale' =>2,
            ])
            ->add('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
