<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',TextType::class,[
                'label' => "Code client"
            ])
            ->add('nomCli',TextType::class,[
                'label' => "Nom du client"
            ])
            ->add('prenomCli',TextType::class,[
                'label' => "Prénom du client"
            ])
            ->add('contact',TextType::class,[
                'required'=> false
            ])
           
            ->add('addresseCli',TextType::class,[
                'label' => "Adresse",
                'required' => false
            ])
            ->add('materiel',EntityType::class,[
                'class' => Materiel::class,
                'choice_label' => "designation",
            ])
            ->add('qteAchete',IntegerType::class,[
               'label' => "Quantité acheté",
               'attr' => [
                   'min' => 0
               ]
            ])
            ->add('status',ChoiceType::class,[
                'choices' => [
                    'Facture' => "Facture",
                    'Proforma' => "Proformat"
                ]
            ])
            // ->add('dateAchat',DateType::class,[
            //     'label' => "Date d'achat",
            //     'widget' => "single_text"
            // ])
            ->add('TypePaiement',ChoiceType::class,[
                'choices' => [
                    'Payer' => "Payer",
                    'Abonnée' => "Abonnée"
                ],
                'label' => "Type de paiement"
            ])
            ->add('avance',TextType::class,[
                'label' => "Avancement",
                'required' => false
            ])
            ->add('dateDecheance',DateType::class,[
                'label' => "Date de décheance",
                'widget' => "single_text",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
