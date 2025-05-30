<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/orders', name: 'admin_orders_')]
#[IsGranted('ROLE_ADMIN')]
class OrderController extends AbstractController
{
    private $entityManager;
    private $orderRepository;

    public function __construct(EntityManagerInterface $entityManager, OrderRepository $orderRepository)
    {
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/orders.html.twig', [
            'orders' => $this->orderRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/{id}/update-status', name: 'update_status', methods: ['POST'])]
    public function updateStatus(Request $request, Order $order): Response
    {
        $status = $request->request->get('status');
        if (in_array($status, [0, 1, 2])) { // 0: Pending, 1: Paid, 2: Cancelled
            $order->setState($status);
            $this->entityManager->flush();
            $this->addFlash('success', 'Order status updated successfully.');
        }

        return $this->redirectToRoute('admin_orders_index');
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Order $order): Response
    {
        $this->entityManager->remove($order);
        $this->entityManager->flush();

        $this->addFlash('success', 'Order deleted successfully.');
        return $this->redirectToRoute('admin_orders_index');
    }
} 