<?php

namespace App\Form\Staff\Care;

use App\Entity\Care;
use App\Entity\CategoryHealth;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use App\Repository\CategoryHealthRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class AddCareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>"Nom des soins"
            ])
            ->add('price', MoneyType::class,[
                'currency'=>'USD',
                'label'=>'Prix'
                ])
            ->add('category', EntityType::class, [
                'label'=> "Type de soin",
                'class' => CategoryHealth::class,
                'choice_label' => 'name',
                'query_builder' => function (CategoryHealthRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('cg')
                        ->orderBy('cg.position', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Care::class,
        ]);
    }
}
