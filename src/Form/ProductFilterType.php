<?php

namespace App\Form;

use App\Model\ProductFilter;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'All Categories',
            ])
            ->add('minPrice', NumberType::class, [
                'required' => false,
                'label' => 'Min Price',
                'attr' => [
                    'placeholder' => 'Min',
                    'class' => 'form-control'
                ]
            ])
            ->add('maxPrice', NumberType::class, [
                'required' => false,
                'label' => 'Max Price',
                'attr' => [
                    'placeholder' => 'Max',
                    'class' => 'form-control'
                ]
            ])
            ->add('availability', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'All',
                'choices' => [
                    'In Stock' => 'in_stock',
                    'Out of Stock' => 'out_of_stock'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFilter::class,
            'method' => 'GET',
        ]);
    }
}
