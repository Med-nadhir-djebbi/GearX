<?php

namespace App\Repository;

use App\Entity\SalesReport;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SalesReport>
 */
class SalesReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SalesReport::class);
    }

    public function generateDailyReport(\DateTime $date): SalesReport
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        // Get orders for the specified date
        $orders = $qb->select('o')
            ->from(Order::class, 'o')
            ->where('DATE(o.orderDate) = :date')
            ->andWhere('o.status = :status')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('status', 'completed')
            ->getQuery()
            ->getResult();

        $report = new SalesReport();
        $report->setDate($date);
        
        // Calculate totals
        $totalRevenue = 0;
        $totalProducts = 0;
        $productCounts = [];
        $categoryDistribution = [];

        foreach ($orders as $order) {
            $totalRevenue += $order->getTotalAmount();
            
            foreach ($order->getOrderItems() as $item) {
                $totalProducts += $item->getQuantity();
                
                // Track product sales
                $productId = $item->getProduct()->getId();
                if (!isset($productCounts[$productId])) {
                    $productCounts[$productId] = [
                        'id' => $productId,
                        'name' => $item->getProduct()->getName(),
                        'quantity' => 0,
                        'revenue' => 0
                    ];
                }
                $productCounts[$productId]['quantity'] += $item->getQuantity();
                $productCounts[$productId]['revenue'] += $item->getPrice() * $item->getQuantity();

                // Track category distribution
                $categoryId = $item->getProduct()->getCategory()->getId();
                $categoryName = $item->getProduct()->getCategory()->getName();
                if (!isset($categoryDistribution[$categoryId])) {
                    $categoryDistribution[$categoryId] = [
                        'name' => $categoryName,
                        'quantity' => 0,
                        'revenue' => 0
                    ];
                }
                $categoryDistribution[$categoryId]['quantity'] += $item->getQuantity();
                $categoryDistribution[$categoryId]['revenue'] += $item->getPrice() * $item->getQuantity();
            }
        }

        // Sort products by revenue and get top 5
        uasort($productCounts, function($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });
        $topProducts = array_slice($productCounts, 0, 5);

        $report->setTotalOrders(count($orders));
        $report->setTotalRevenue($totalRevenue);
        $report->setTotalProducts($totalProducts);
        $report->setAverageOrderValue($totalRevenue > 0 ? $totalRevenue / count($orders) : 0);
        $report->setTopProducts($topProducts);
        $report->setCategoryDistribution($categoryDistribution);

        return $report;
    }

    public function getReportsByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('sr')
            ->andWhere('sr.date >= :startDate')
            ->andWhere('sr.date <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('sr.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
} 