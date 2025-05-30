<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Informatique' => [
                'Ordinateurs portables',
                'PC de bureau',
                'Composants PC',
                'Périphériques'
            ],
            'Smartphones' => [
                'Apple',
                'Samsung',
                'Xiaomi',
                'OnePlus'
            ],
            'Gaming' => [
                'Consoles',
                'Jeux vidéo',
                'Accessoires gaming',
                'PC Gaming'
            ],
            'Audio' => [
                'Casques',
                'Écouteurs',
                'Enceintes',
                'Microphones'
            ]
        ];

        foreach ($categories as $parentCategoryName => $subcategories) {
            // Create parent category
            $parentCategory = new Category();
            $parentCategory->setName($parentCategoryName);
            $manager->persist($parentCategory);

            foreach ($subcategories as $subcategoryName) {
                // Create subcategory
                $subcategory = new Category();
                $subcategory->setName($subcategoryName);
                $subcategory->setParent($parentCategory); // Set parent category
                $manager->persist($subcategory);
            }
        }

        $manager->flush(); // Save all categories and subcategories to the database
    }
}