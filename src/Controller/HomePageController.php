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
        
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
        ]);
    }
    #[Route('/template', name: 'template')]
    public function template(EntityManagerInterface $entityManager): Response
    {
        // Get all categories including their children
        $categories = $entityManager->getRepository(Category::class)
            ->createQueryBuilder('c')
            ->leftJoin('c.children', 'children')
            ->where('c.parent IS NULL')
            ->getQuery()
            ->getResult();

        return $this->render('home_page/template.html.twig', [
            'categories' => $categories
        ]);
    }

}
