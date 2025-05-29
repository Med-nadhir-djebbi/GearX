<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, ProductRepository $productRepository): Response
    {
        $query = $request->query->get('q');
        $products = [];
        $total = 0;
        
        if ($query && strlen($query) >= 2) {
            // Split query into words for better matching
            $searchTerms = array_filter(explode(' ', $query));
            $qb = $productRepository->createQueryBuilder('p')
                ->leftJoin('p.category', 'c');
            
            $conditions = [];
            $parameters = [];
            
            foreach ($searchTerms as $key => $term) {
                $conditions[] = '(LOWER(p.name) LIKE LOWER(:term' . $key . ') OR LOWER(p.description) LIKE LOWER(:term' . $key . ') OR LOWER(c.name) LIKE LOWER(:term' . $key . '))';
                $parameters['term' . $key] = '%' . $term . '%';
            }
            
            if (!empty($conditions)) {
                $qb->where(implode(' AND ', $conditions))
                   ->orderBy('p.name', 'ASC');
                
                // Set parameters individually to avoid ArrayCollection requirement
                foreach ($parameters as $key => $value) {
                    $qb->setParameter($key, $value);
                }
                
                // For AJAX requests, limit results
                if ($request->query->get('ajax')) {
                    $qb->setMaxResults(5);
                }
                
                $products = $qb->getQuery()->getResult();
                $total = count($products);
            }
        }
        
        if ($request->query->get('ajax')) {
            return $this->render('search/_results.html.twig', [
                'results' => $products,
                'count' => $total,
                'query' => $query,
            ]);
        }
        
        return $this->render('search/index.html.twig', [
            'query' => $query,
            'products' => $products,
            'total' => $total,
        ]);
    }
}
