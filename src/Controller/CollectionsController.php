<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\ProductFilterType;
use App\Model\ProductFilter;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CollectionsController extends AbstractController
{
    #[Route('/collections', name: 'app_collections')]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $filter = new ProductFilter();
        $form = $this->createForm(ProductFilterType::class, $filter);
        $form->handleRequest($request);

        // Get selected category IDs from request
        $selectedCategoryIds = (array) $request->query->all('categories');
        
        // Clear and add selected categories to filter
        $filter->getCategories()->clear();
        foreach ($selectedCategoryIds as $categoryId) {
            if ($category = $categoryRepository->find($categoryId)) {
                $filter->addCategory($category);
            }
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $result = $productRepository->findByFilter($filter, $page, $limit);
        
        return $this->render('collections/index.html.twig', [
            'filter_form' => $form->createView(),
            'products' => $result['products'],
            'currentPage' => $page,
            'maxPages' => ceil($result['totalProducts'] / $limit),
            'totalProducts' => $result['totalProducts'],
            'categories' => $categoryRepository->findAll(),
            'selectedCategories' => $filter->getCategories()
        ]);
    }
}