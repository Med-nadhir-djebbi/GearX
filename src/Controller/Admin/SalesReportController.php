<?php

namespace App\Controller\Admin;

use App\Entity\SalesReport;
use App\Repository\SalesReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/sales')]
class SalesReportController extends AbstractController
{
    #[Route('/', name: 'app_admin_sales_index')]
    public function index(Request $request, SalesReportRepository $salesReportRepository): Response
    {
        $startDate = new \DateTime($request->query->get('start_date', '-30 days'));
        $endDate = new \DateTime($request->query->get('end_date', 'today'));

        $reports = $salesReportRepository->getReportsByDateRange($startDate, $endDate);

        // Calculate summary statistics
        $totalRevenue = 0;
        $totalOrders = 0;
        $totalProducts = 0;
        $averageOrderValue = 0;

        foreach ($reports as $report) {
            $totalRevenue += $report->getTotalRevenue();
            $totalOrders += $report->getTotalOrders();
            $totalProducts += $report->getTotalProducts();
        }

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return $this->render('admin/sales/index.html.twig', [
            'reports' => $reports,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => [
                'totalRevenue' => $totalRevenue,
                'totalOrders' => $totalOrders,
                'totalProducts' => $totalProducts,
                'averageOrderValue' => $averageOrderValue,
            ],
        ]);
    }

    #[Route('/generate/{date}', name: 'app_admin_sales_generate')]
    public function generate(
        string $date,
        SalesReportRepository $salesReportRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $reportDate = new \DateTime($date);
        $report = $salesReportRepository->generateDailyReport($reportDate);
        
        $entityManager->persist($report);
        $entityManager->flush();

        $this->addFlash('success', 'Sales report generated successfully.');

        return $this->redirectToRoute('app_admin_sales_index');
    }

    #[Route('/report/{id}', name: 'app_admin_sales_show')]
    public function show(SalesReport $report): Response
    {
        return $this->render('admin/sales/show.html.twig', [
            'report' => $report,
        ]);
    }
} 