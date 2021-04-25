<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Categorie;
use App\Entity\Departement;
use App\Entity\SousType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qte',IntegerType::class,[
                'label' => "Quantité",
                'attr' => [
                    'min' => 0
                ]
            ])
            ->add('dateApprovisionnement',DateType::class,[
                'label' => "Date d'approvisionnement",
                'widget' => "single_text"
            ])
            ->add('designation',TextType::class,[
                'label' => "Réference"
            ])
            ->add('prixUnitaire',IntegerType::class,[
                'label' => "Prix unitaire",
                'attr' => [
                    'min' => 0
                ]
            ])
          
            ->add('type',ChoiceType::class,[
                'choices' => [
                    'Légère' => "Légère",
                    'Minibus' => "Minibus",
                    'Camion' => "Camion"
                ]
            ])

            ->add('sousType',EntityType::class,[
                'class' => SousType::class,
                'choice_label' => "descrSousType",
                'mapped' => false
            ])

            ->add('departement',EntityType::class,[
                'class' => Departement::class,
                'choice_label' => "nomDepartement",
            ])
        ;
        $builder->get('sousType')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event)
            {
                // dd($event);
                $form = $event->getForm();
                // dd($form);

                $form->getParent()->add('categorie',EntityType::class,[
                    'class' => Categorie::class,
                    'choices' => $form->getData()->getCategories()
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
