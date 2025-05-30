<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Model\Cart;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    public function index(Cart $cart, EntityManagerInterface $entityManager): Response
    {
        // Check if user is logged in
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $cartDetails = $cart->getDetails();

        // Check if cart is empty
        if (empty($cartDetails['products'])) {
            return $this->redirectToRoute('cart');
        }

        return $this->render('order/index.html.twig', [
            'cart' => $cartDetails['products'],
            'total' => $cartDetails['totals']['price']
        ]);
    }

    #[Route('/order/create', name: 'order_create', methods: ['POST'])]
    public function create(
        Cart $cart, 
        EntityManagerInterface $entityManager, 
        SessionInterface $session,
        EmailService $emailService
    ): Response {
        // Check if user is logged in
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $cartData = $cart->getDetails();

        // Create new order
        $order = new Order();
        $user = $this->getUser();
        $user->addOrder($order);  // This will also set the user on the order
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setState(0); // 0 = not paid
        $order->setReference(uniqid());

        // Add order details
        foreach ($cartData['products'] as $item) {
            $orderDetails = new OrderDetails();
            $orderDetails->setBindedOrder($order);
            $orderDetails->setProduct($item['product']->getName()); // Store the product name as string
            $orderDetails->setQuantity($item['quantity']);
            $orderDetails->setPrice($item['product']->getPrice());
            $orderDetails->setTotal($item['product']->getPrice() * $item['quantity']);
            
            $entityManager->persist($orderDetails);
            
            // Update product stock
            $product = $item['product'];
            $product->setStock($product->getStock() - $item['quantity']);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        // Send order confirmation email
        $emailService->send(
            $user->getEmail(),
            $user->getUsername(),
            'Order Confirmation - GearX',
            'emails/order_confirmation.html.twig',
            ['order' => $order]
        );

        // Clear the cart
        $cart->remove();

        $this->addFlash('success', 'Order placed successfully!');
        return $this->redirectToRoute('account_orders');
    }
}
