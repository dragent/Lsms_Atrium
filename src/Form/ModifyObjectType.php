<?php

namespace App\Form;

use App\Entity\Objects;
use App\Form\Staff\Recipe\QuantityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ModifyObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
          $builder
            ->add('quantity',IntegerType::class,[
                'label'=>'Quantité',
            ])
            ->add('quantityTrigger',IntegerType::class,[
                'label'=>'Seuil limite',
            ])
            ->add('buyPrice',MoneyType::class,[
                'currency'=>'USD',
                'label'=>'Prix d\'achat',
                'required'=>false,
            ])
            ->add("quantitiesComponent", CollectionType::class,[
                'entry_type'=>QuantityType::class,
                'by_reference'=>false,
                'allow_add'=>true,
                'allow_delete'=>true,
                'entry_options'=>[
                    'label'=> false
                ],
                'label'=> "Composants"
                
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Objects::class,
        ]);
    }
}
