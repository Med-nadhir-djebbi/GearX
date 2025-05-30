<?php

namespace App\Form;

use App\Entity\Category;
use App\Model\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class SearchType extends AbstractType
{

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('string', TextType::class, [
                'label' => 'Search',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search products...',
                    'class' => 'form-control'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Categories',
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('minPrice', NumberType::class, [
                'label' => 'Min Price',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Min price',
                    'class' => 'form-control'
                ]
            ])
            ->add('maxPrice', NumberType::class, [
                'label' => 'Max Price',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Max price',
                    'class' => 'form-control'
                ]
            ])
            ->add('availability', ChoiceType::class, [
                'label' => 'Availability',
                'required' => false,
                'choices' => [
                    'All' => null,
                    'In Stock' => 'in_stock',
                    'Out of Stock' => 'out_of_stock'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('sortBy', ChoiceType::class, [
                'label' => 'Sort By',
                'required' => false,
                'choices' => [
                    'Name' => 'name',
                    'Price' => 'price',
                    'Rating' => 'rating'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('sortOrder', ChoiceType::class, [
                'label' => 'Sort Order',
                'required' => false,
                'choices' => [
                    'Ascending' => 'asc',
                    'Descending' => 'desc'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-outline-info w-100'
                ]
            ])
        ;
    }

    public function getBlockPrefix() {
        return '';
    }
}