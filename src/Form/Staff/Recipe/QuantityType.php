<?php

namespace App\Form\Staff\Recipe;

use App\Entity\Objects;
use App\Entity\Quantity;
use Doctrine\ORM\QueryBuilder;
use App\Repository\ObjectsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class QuantityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('component', EntityType::class, [
                'class' => Objects::class,
                'choice_label' => 'name',
                'label' =>'Composants',
                'query_builder' => function (ObjectsRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('quantity', NumberType::class,[
            'label'=>'Quantité'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quantity::class,
        ]);
    }
}
