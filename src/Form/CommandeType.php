<?php

namespace App\Form;

use DateTime;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_arrivee', DateType::class, [
                'widget' => 'single_text',  // permet de choisir l'affichage d'un calendrier (voir doc datetimetype)
                'attr' => [
                    'min' => (new DateTime())->format('Y-m-d'), // permet d'empecher de choisir une date ultérieure à celle d'aujourd'hui (voir doc datetime)
                ]
            ])
    
            ->add('date_depart', DateType::class, [
                'widget' => 'single_text',  // permet de choisir l'affichage d'un calendrier (voir doc datetimetype)
                'attr' => [
                    'min' => (new DateTime())->format('Y-m-d'), // permet d'empecher de choisir une date ultérieure à celle d'aujourd'hui (voir doc datetime)
                ]
            ])
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
