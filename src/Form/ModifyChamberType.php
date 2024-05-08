<?php

namespace App\Form;

use App\Entity\Chamber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ModifyChamberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type',ChoiceType::class,[
                'choices' => [
                    "Type de Chambre"=>[
                    "Chambre Seule"=>0,
                    "Chambre Double"=>1,
                    "Chambre Triple"=>2,
                    ],
                ],
                'label'=>'Type de chambre',
            ])
            ->add('price',MoneyType::class,[
                'currency'=>'USD',
                'label'=>'Prix de la chambre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chamber::class,
        ]);
    }
}
