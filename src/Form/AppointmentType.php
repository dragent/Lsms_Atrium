<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason',ChoiceType::class, [
                'choices'  => [
                    'Visite Medical' => "medicalVisit",
                    'Permis Port d\'Arme' => "ppa",
                    'Test physique' => "testPhy",
                    'Rendez-vous avec Psychologue' => "rdvPsy",
                    'Autre' => "Autre",
                ],
                'label'=>"Raison du rendez-vous"
            ])
            ->add('number',TelType::class,[
                'label'=>'Numéro de téléphone',
            ])
            ->add('detail',TextareaType::class,[
                'label'=>"Détail du Rendez vous"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
