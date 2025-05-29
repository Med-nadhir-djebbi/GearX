<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get categories from the database
        $categories = $entityManager->getRepository(Category::class)->findAll();
        
        // Get featured products
        $featuredProducts = $entityManager->getRepository(Product::class)
            ->findBy([], ['id' => 'DESC'], 8);
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomePageController',
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
        ]);
    }
    #[Route('/template', name: 'tempp')]
    public function about(EntityManagerInterface $entityManager): Response
    {

        $existingCategories = $entityManager->getRepository(Category::class)->findAll();

        if (empty($existingCategories)) {
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

        $mainCategories = $entityManager->getRepository(Category::class)->findBy(['parent' => null]);

        return $this->render('home/index.html.twig', [
            'categories' => $mainCategories
        ]);
    }

}
