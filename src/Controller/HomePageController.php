<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }
    #[Route('/template', name: 'tempp')]
    public function about(EntityManagerInterface $entityManager): Response
    {
        // Sample categories data
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

        // Check if categories already exist
        $existingCategories = $entityManager->getRepository(Category::class)->findAll();

        if (empty($existingCategories)) {
            // Create categories and subcategories
            foreach ($categories as $categoryName => $subcategories) {
                $category = new Category();
                $category->setName($categoryName);
                $entityManager->persist($category);

                foreach ($subcategories as $subcategoryName) {
                    $subcategory = new Category();
                    $subcategory->setName($subcategoryName);
                    $subcategory->setParent($category);
                    $entityManager->persist($subcategory);
                }
            }

            $entityManager->flush();
        }

        // Get all main categories with their subcategories
        $mainCategories = $entityManager->getRepository(Category::class)->findBy(['parent' => null]);

        return $this->render('base.html.twig', [
            'categories' => $mainCategories
        ]);
    }

}
