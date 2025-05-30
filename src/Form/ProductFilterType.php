<?php

namespace App\Form;

use App\Model\ProductFilter;
use App\Entity\Category;
use App\Repository\CategoryRepository;
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
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.parent IS NULL')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'gaming-checkbox'];
                },
                'label_attr' => [
                    'class' => 'flex items-center space-x-3 hover:text-lime-400 transition-colors'
                ],
                'row_attr' => [
                    'class' => 'category-item'
                ],
                'group_by' => function($choice, $key, $value) {
                    return $choice->getParent() ? $choice->getParent()->getName() : null;
                }
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
                'expanded' => true,
                'label' => false,
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'gaming-radio'];
                },
                'label_attr' => [
                    'class' => 'flex items-center space-x-3 hover:text-lime-400 transition-colors'
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
