<?php

namespace App\Controller;

use App\Form\ProductFilterType;
use App\Model\ProductFilter;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CollectionsController extends AbstractController
{
    #[Route('/collections', name: 'app_collections')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $filter = new ProductFilter();
        $form = $this->createForm(ProductFilterType::class, $filter);
        
        // Handle the form submission
        $form->handleRequest($request);
        
        // Get selected category IDs from request
        $selectedCategoryIds = $request->query->all('categories');
        if (!empty($selectedCategoryIds)) {
            // Find and add selected categories to filter
            foreach ($selectedCategoryIds as $categoryId) {
                $category = $categoryRepository->find($categoryId);
                if ($category) {
                    $filter->addCategory($category);
                }
            }
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $result = $productRepository->findByFilter($filter, $page, $limit);
        $products = $result['products'];
        $totalProducts = $result['totalProducts'];

        $maxPages = ceil($totalProducts / $limit);

        return $this->render('collections/index.html.twig', [
            'filter_form' => $form,
            'products' => $products,
            'currentPage' => $page,
            'maxPages' => $maxPages,
            'totalProducts' => $totalProducts,
            'categories' => $categoryRepository->findBy(['parent' => null])
        ]);
    }
}
