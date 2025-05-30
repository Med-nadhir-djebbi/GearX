<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Category;
use App\Model\Search;
use App\Model\ProductFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    private function getAllSubcategoryIds($category): array
    {
        $ids = [$category->getId()];
        foreach ($category->getSubcategories() as $subcategory) {
            $ids = array_merge($ids, $this->getAllSubcategoryIds($subcategory));
        }
        return $ids;
    }

    public function findWithSearch(Search $search): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p', 'c')
            ->leftJoin('p.category', 'c');

        if ($search->getString()) {
            $query = $query
                ->andWhere('p.name LIKE :string OR p.description LIKE :string')
                ->setParameter('string', "%{$search->getString()}%");
        }

        if (!empty($search->getCategories())) {
            $allCategoryIds = [];
            foreach ($search->getCategories() as $category) {
                $allCategoryIds = array_merge($allCategoryIds, $this->getAllSubcategoryIds($category));
            }
            if (!empty($allCategoryIds)) {
                $query->andWhere('p.category IN (:categories)')
                    ->setParameter('categories', array_unique($allCategoryIds));
            }
        }

        if ($search->getMinPrice()) {
            $query->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $search->getMinPrice());
        }

        if ($search->getMaxPrice()) {
            $query->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $search->getMaxPrice());
        }

        if ($search->getAvailability()) {
            if ($search->getAvailability() === 'in_stock') {
                $query->andWhere('p.stock > 0');
            } elseif ($search->getAvailability() === 'out_of_stock') {
                $query->andWhere('p.stock = 0');
            }
        }

        // Handle sorting
        $sortBy = $search->getSortBy() ?: 'name';
        $sortOrder = $search->getSortOrder() ?: 'asc';
        
        // Validate sort options
        $allowedSortFields = ['name', 'price', 'rating'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        
        $query->orderBy('p.' . $sortBy, $sortOrder);

        return $query->getQuery()->getResult();
    }

    public function findByFilter(ProductFilter $filter, int $page = 1, int $limit = 10): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p', 'c')
            ->leftJoin('p.category', 'c');

        // Apply category filter
        if (!$filter->getCategories()->isEmpty()) {
            $categoryIds = [];
            foreach ($filter->getCategories() as $category) {
                // Add the selected category
                $categoryIds[] = $category->getId();
                
                // Add all subcategories recursively
                $this->addSubcategoryIds($category, $categoryIds);
            }
            
            if (!empty($categoryIds)) {
                $query->andWhere('p.category IN (:categories)')
                    ->setParameter('categories', array_unique($categoryIds));
            }
        }

        // Apply price filters
        if ($filter->getMinPrice()) {
            $query->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $filter->getMinPrice());
        }

        if ($filter->getMaxPrice()) {
            $query->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $filter->getMaxPrice());
        }

        // Apply availability filter
        if ($filter->getAvailability()) {
            if ($filter->getAvailability() === 'in_stock') {
                $query->andWhere('p.stock > 0');
            } elseif ($filter->getAvailability() === 'out_of_stock') {
                $query->andWhere('p.stock = 0');
            }
        }

        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;

        // Get total count before applying limit
        $totalQuery = clone $query;
        $totalProducts = count($totalQuery->getQuery()->getResult());

        // Apply pagination
        $query->setMaxResults($limit)
            ->setFirstResult($offset);

        return [
            'products' => $query->getQuery()->getResult(),
            'totalProducts' => $totalProducts
        ];
    }

    private function addSubcategoryIds(Category $category, array &$ids): void
    {
        foreach ($category->getSubcategories() as $subcategory) {
            $ids[] = $subcategory->getId();
            $this->addSubcategoryIds($subcategory, $ids);
        }
    }
}
