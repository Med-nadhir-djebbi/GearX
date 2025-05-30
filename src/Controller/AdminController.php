<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        UserRepository $userRepository
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'total_orders' => $orderRepository->count([]),
            'total_products' => $productRepository->count([]),
            'total_users' => $userRepository->count([]),
            'recent_orders' => $orderRepository->findBy([], ['createdAt' => 'DESC'], 5),
        ]);
    }

    #[Route('/products', name: 'admin_products')]
    public function products(ProductRepository $productRepository): Response
    {
        return $this->render('admin/products.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/orders', name: 'admin_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/orders.html.twig', [
            'orders' => $orderRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/dropships', name: 'admin_dropships')]
    public function dropships(): Response
    {
        return $this->render('admin/dropships.html.twig', [
            'dropships' => [], // You'll need to implement dropship functionality
        ]);
    }
}