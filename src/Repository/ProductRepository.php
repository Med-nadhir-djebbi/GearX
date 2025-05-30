<?php

namespace App\Repository;

use App\Entity\Product;
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

    public function findByFilter($filter, int $page = 1, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('p');

        if (!empty($filter->getCategories())) {
            $allCategoryIds = [];
            foreach ($filter->getCategories() as $categoryId) {
                $category = $this->getEntityManager()->getRepository(Category::class)->find($categoryId);
                if ($category) {
                    $allCategoryIds = array_merge($allCategoryIds, $this->getAllSubcategoryIds($category));
                }
            }
            if (!empty($allCategoryIds)) {
                $qb->andWhere('p.category IN (:categories)')
                   ->setParameter('categories', array_unique($allCategoryIds));
            }
        }

        if ($filter->getMinPrice()) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $filter->getMinPrice());
        }

        if ($filter->getMaxPrice()) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $filter->getMaxPrice());
        }

        if ($filter->getAvailability()) {
            if ($filter->getAvailability() === 'in_stock') {
                $qb->andWhere('p.stock > 0');
            } elseif ($filter->getAvailability() === 'out_of_stock') {
                $qb->andWhere('p.stock = 0');
            }
        }

        $qb->orderBy('p.name', 'ASC');

        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;

        return [
            'products' => $qb->setMaxResults($limit)
                          ->setFirstResult($offset)
                          ->getQuery()
                          ->getResult(),
            'totalProducts' => count($qb->getQuery()->getResult()),
        ];
    }
}
