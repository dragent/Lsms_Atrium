<?php

namespace App\Form;

use App\Entity\Objects;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom'
            ])
            ->add('quantity',IntegerType::class,[
                'label'=>'Quantité',
            ])
            ->add('quantityTrigger',IntegerType::class,[
                'label'=>'Seuil limite',
            ])
            ->add('buyPrice',IntegerType::class,[
                'label'=>'Prix d\'achat',
                'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Objects::class,
        ]);
    }
}