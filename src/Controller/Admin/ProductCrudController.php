<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Product')
            ->setEntityLabelInPlural('Products')
            ->setPageTitle('index', 'Products')
            ->setPageTitle('new', 'Add Product')
            ->setPageTitle('edit', fn (Product $product) => sprintf('Edit %s', $product->getName()))
            ->setDefaultSort(['id' => 'DESC'])
            ->overrideTemplate('crud/index', 'bundles/EasyAdminBundle/crud/product.html.twig')
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setRequired(true)
                ->setHelp('Enter the product name'),
                
            TextareaField::new('description')
                ->setRequired(true)
                ->setHelp('Enter a detailed description')
                ->hideOnIndex(),
                
            MoneyField::new('price')
                ->setRequired(true)
                ->setCurrency('EUR')
                ->setHelp('Enter the price in euros'),
                
            NumberField::new('stock')
                ->setRequired(true)
                ->setHelp('Enter the available stock'),
                
            ImageField::new('image')
                ->setBasePath('uploads/products')
                ->setUploadDir('public/uploads/products')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setHelp('Upload a product image'),
                
            AssociationField::new('category')
                ->setRequired(true)
                ->setHelp('Select the product category'),
                
            NumberField::new('rating')
                ->setHelp('Product rating (0-5)')
                ->setNumDecimals(1)
                ->hideOnForm(),
        ];
    }
}
