<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\EmailService;
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
    private $emailService;

    public function __construct(
        EntityManagerInterface $entityManager, 
        OrderRepository $orderRepository,
        EmailService $emailService
    ) {
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
        $this->emailService = $emailService;
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
            $oldStatus = $order->getState();
            $order->setState($status);
            $this->entityManager->flush();

            // Send email notification based on status change
            $statusLabels = [
                0 => 'Pending',
                1 => 'Paid',
                2 => 'Cancelled'
            ];

            $this->emailService->send(
                $order->getUser()->getEmail(),
                $order->getUser()->getUsername(),
                'Order Status Updated - GearX',
                'emails/order_status_update.html.twig',
                [
                    'order' => $order,
                    'oldStatus' => $statusLabels[$oldStatus],
                    'newStatus' => $statusLabels[$status]
                ]
            );

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