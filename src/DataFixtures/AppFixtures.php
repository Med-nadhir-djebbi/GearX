<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Define main categories first
        $mainCategories = [
            'Informatique',
            'Composants',
            'Gaming',
            'Périphériques'
        ];

        // Create main categories
        foreach ($mainCategories as $mainCategoryName) {
            $category = new Category();
            $category->setName($mainCategoryName);
            $manager->persist($category);
            $this->addReference('category_' . $mainCategoryName, $category);
        }

        // Define subcategories with their parent categories
        $subcategories = [
            'Informatique' => [
                'Ordinateurs portables',
                'PC de bureau'
            ],
            'Composants' => [
                'Processeurs',
                'Cartes graphiques',
                'RAM',
                'Refroidissements',
                'Stockage'
            ],
            'Gaming' => [
                'Consoles',
                'Jeux vidéo'
            ],
            'Périphériques' => [
                'Souris',
                'Claviers',
                'Moniteurs',
                'Webcams',
                'Casques'
            ]
        ];

        // Create subcategories
        foreach ($subcategories as $parentName => $childCategories) {
            $parent = $this->getReference('category_' . $parentName, Category::class);
            
            foreach ($childCategories as $childName) {
                $child = new Category();
                $child->setName($childName);
                $child->setParent($parent);
                $manager->persist($child);
                $this->addReference('category_' . $childName, $child);
            }
        }

        // Define third-level categories
        $thirdLevel = [
            'Processeurs' => ['Intel', 'AMD Ryzen'],
            'Cartes graphiques' => ['NVIDIA', 'AMD Radeon'],
            'Refroidissements' => ['Air', 'Liquide'],
            'Stockage' => ['SSD', 'HDD']
        ];

        // Create third-level categories
        foreach ($thirdLevel as $parentName => $childCategories) {
            $parent = $this->getReference('category_' . $parentName, Category::class);
            
            foreach ($childCategories as $childName) {
                $child = new Category();
                $child->setName($childName);
                $child->setParent($parent);
                $manager->persist($child);
                $this->addReference('category_' . $childName, $child);
            }
        }

        $manager->flush();
    }
}
