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
        EmailService $emailService,$stripeSK
    ): Response {
        // Check if the user is logged in
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $cartData = $cart->getDetails(); // Get cart details

        // Create a new Order object
        $order = new Order();
        $user = $this->getUser();
        $user->addOrder($order);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setState(0); // Set initial state as "not paid"
        $order->setReference(uniqid()); // Generate unique reference for the order

        // Calculate total price and prepare product details for Stripe
        $lineItems = [];
        $totalPrice = 0;

        foreach ($cartData['products'] as $item) {
            $productPrice = $item['product']->getPrice() * 100; // Product price in cents (Stripe format)
            $quantity = $item['quantity'];

            // Add the product's details to the line items for Stripe
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['product']->getName(),
                    ],
                    'unit_amount' => $productPrice, // Unit price in cents
                ],
                'quantity' => $quantity, // Quantity purchased
            ];

            // Calculate total price of this product (price × quantity)
            $totalPrice += $productPrice * $quantity;

            // Create OrderDetails object for the database
            $orderDetails = new OrderDetails();
            $orderDetails->setBindedOrder($order);
            $orderDetails->setProduct($item['product']->getName());
            $orderDetails->setQuantity($quantity);
            $orderDetails->setPrice($item['product']->getPrice());
            $orderDetails->setTotal($item['product']->getPrice() * $quantity);

            $entityManager->persist($orderDetails);
        }

        // Add a shipping fee as a line item (e.g., $6)
        $shippingFee = 600;
        $totalPrice += $shippingFee;
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Total Price',
                ],
                'unit_amount' => $totalPrice,
            ],
            'quantity' => 1,
        ];

        // Add the shipping fee to the total price


        // Persist the order and save to the database
        $entityManager->persist($order);
        $entityManager->flush();

        // Prepare Stripe Checkout Session
        \Stripe\Stripe::setApiKey($stripeSK);;

        try {
            // Create a new Stripe Checkout session
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems, // Send all line items (products and shipping)
                'mode' => 'payment', // Payment mode
                // Define success and cancel URLs
                'success_url' => $this->generateUrl('order_payment_success', ['reference' => $order->getReference()], 0),
                'cancel_url' => $this->generateUrl('order_payment_cancel', ['reference' => $order->getReference()], 0),
            ]);

            // Redirect user to Stripe's hosted checkout page
            return $this->redirect($checkoutSession->url);
        } catch (\Exception $e) {
            // Add an error message and redirect the user back to the cart page
            $this->addFlash('error', 'There was a problem with your payment: ' . $e->getMessage());
            return $this->redirectToRoute('cart');
        }
    }
    #[Route('/order/payment/success/{reference}', name: 'order_payment_success', methods: ['GET'])]
    public function paymentSuccess(string $reference, EntityManagerInterface $entityManager, Cart $cart): Response {
        // Find the order by its reference
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if (!$order) {
            throw $this->createNotFoundException('Order not found.');
        }

        // Ensure the order was not already processed
        if ($order->getState() === 1) { // Already paid
            return $this->redirectToRoute('account_orders');
        }

        // Mark the order as paid
        $order->setState(1); // 1 = Paid
        $entityManager->flush();

        // Update product stock (permanent change after payment confirmation)
        foreach ($order->getOrderDetails() as $orderDetails) {
            $product = $entityManager->getRepository(Product::class)->findOneBy(['name' => $orderDetails->getProduct()]);
            if ($product) {
                $product->setStock($product->getStock() - $orderDetails->getQuantity());
            }
        }
        $entityManager->flush();

        // Clear the cart
        $cart->remove();

        $this->addFlash('success', 'Your payment was successful, and your order has been placed!');

        // Redirect to user's orders page
        return $this->redirectToRoute('account_orders');
    }
    #[Route('/order/payment/cancel/{reference}', name: 'order_payment_cancel', methods: ['GET'])]
    public function paymentCancel(string $reference, EntityManagerInterface $entityManager): Response {
        // Find the order by its reference
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if (!$order) {
            throw $this->createNotFoundException('Order not found.');
        }

        // Render cancel page
        return $this->render('checkout/cancel.html.twig', [
            'order' => $order,
        ]);
    }
}