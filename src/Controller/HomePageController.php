<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product; // Assuming Product entity is still used
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get categories from the database (if needed for navigation/footer)
        $categories = $entityManager->getRepository(Category::class)->findAll();
        
        // Get featured products
        $featuredProducts = $entityManager->getRepository(Product::class)
            ->findBy([], ['id' => 'DESC'], 8); // Example: last 8 products
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomePageController',
            'categories' => $categories, // Pass categories if your base layout or home page uses them
            'featuredProducts' => $featuredProducts,
        ]);
    }

    #[Route('/template', name: 'tempp')]
    public function about(EntityManagerInterface $entityManager): Response
    {
        // This function seems to be for category seeding or specific category display
        // I will not modify this function, assuming it has a distinct purpose.

        $existingCategories = $entityManager->getRepository(Category::class)->findAll();

        if (empty($existingCategories)) {
            // This block will only execute if no categories exist
            // Example data (you might remove this if categories are managed elsewhere)
            $categoriesToSeed = [
                'Electronics' => ['Laptops', 'Smartphones', 'Tablets'],
                'Gaming' => ['Consoles', 'PC Games', 'Accessories'],
                // Add more as needed
            ];

            foreach ($categoriesToSeed as $categoryName => $subcategories) {
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

        $mainCategories = $entityManager->getRepository(Category::class)->findBy(['parent' => null]);

        return $this->render('home/index.html.twig', [
            'categories' => $mainCategories // Ensure 'categories' is passed for rendering
        ]);
    }

    // NEW: About Us Page Route and Function
    #[Route('/about-us', name: 'app_about_us')]
    public function aboutUs(): Response
    {
        return $this->render('home/about_us.html.twig', [
            'controller_name' => 'HomePageController', // Optional, useful for debugging
        ]);
    }
}