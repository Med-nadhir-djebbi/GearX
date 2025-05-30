<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Dropship;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        // Get total counts
        $totalOrders = $this->entityManager->getRepository(Order::class)->count([]);
        $totalProducts = $this->entityManager->getRepository(Product::class)->count([]);
        $totalUsers = $this->entityManager->getRepository(User::class)->count([]);
        $totalDropships = $this->entityManager->getRepository(Dropship::class)->count(['status' => 'active']);

        // Get recent orders
        $recentOrders = $this->entityManager->getRepository(Order::class)
            ->findBy([], ['createdAt' => 'DESC'], 10);

        // Get latest products
        $latestProducts = $this->entityManager->getRepository(Product::class)
            ->findBy([], ['createdAt' => 'DESC'], 5);

        // Get latest users
        $latestUsers = $this->entityManager->getRepository(User::class)
            ->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'total_orders' => $totalOrders,
            'total_products' => $totalProducts,
            'total_users' => $totalUsers,
            'total_dropships' => $totalDropships,
            'recent_orders' => $recentOrders,
            'latest_products' => $latestProducts,
            'latest_users' => $latestUsers,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GearX Admin')
            ->setFaviconPath('build/images/favicon.ico')
            ->setTranslationDomain('admin')
            ->renderContentMaximized()
            ->renderSidebarMinimized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-gamepad');
        yield MenuItem::section('Store Management');
        yield MenuItem::linkToCrud('Products', 'fas fa-box', Product::class)
            ->setDefaultSort(['id' => 'DESC']);
        yield MenuItem::linkToCrud('Categories', 'fas fa-tags', Category::class);
        yield MenuItem::section('User Management');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Orders', 'fas fa-shopping-cart', Order::class)
            ->setDefaultSort(['id' => 'DESC']);
    }
}
