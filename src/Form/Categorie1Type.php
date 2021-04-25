<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\SousType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Categorie1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, [
                'label' => "Nom du fourniseur"
            ])
            ->add('descriptionCateg', TextType::class, [
                'label' => "Nom/marque du matériel"
            ])
            ->add('sousType',EntityType::class,[
                'class' => SousType::class,
                'choice_label' => "descrSousType",
                'label' => "Type du matériel"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
