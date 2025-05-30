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
            $category->setImage($this->generateImageName($mainCategoryName));
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
                $child->setImage($this->generateImageName($childName));
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
                $child->setImage($this->generateImageName($childName));
                $manager->persist($child);
                $this->addReference('category_' . $childName, $child);
            }
        }

        $manager->flush();
    }

    /**
     * Generate image filename from category name
     */
    private function generateImageName(string $categoryName): string
    {
        // Convert category name to lowercase and replace spaces/special chars
        $imageName = strtolower($categoryName);
        $imageName = str_replace(['é', 'è', 'ê', 'ë'], 'e', $imageName);
        $imageName = str_replace(['à', 'á', 'â', 'ã', 'ä'], 'a', $imageName);
        $imageName = str_replace(['ù', 'ú', 'û', 'ü'], 'u', $imageName);
        $imageName = str_replace(['ç'], 'c', $imageName);
        $imageName = preg_replace('/[^a-z0-9]+/', '_', $imageName);
        $imageName = trim($imageName, '_');
        
        return $imageName . '.webp';
    }
}