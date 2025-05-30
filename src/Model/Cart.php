<?php
namespace App\Model;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart 
{
    private $session;
    private $repository;

    public function __construct(RequestStack $requestStack, ProductRepository $repository)
    {
        $this->session = $requestStack->getSession();
        $this->repository = $repository;
    }


    /**
     *
     * @param int $id
     * @return void
     */
    public function add(int $id, int $quantity = 1): void
    {
        $cart = $this->session->get('cart', []);
        $product = $this->repository->find($id);

        if (!$product) {
            return;
        }

        // Make sure quantity doesn't exceed stock
        $currentQuantity = $cart[$id] ?? 0;
        $newQuantity = $currentQuantity + $quantity;
        
        if ($newQuantity > $product->getStock()) {
            $newQuantity = $product->getStock();
        }

        $cart[$id] = $newQuantity;
        $this->session->set('cart', $cart);
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->session->get('cart');
    }


    /**
     * @return void
     */
    public function remove(): void
    {
        $this->session->remove('cart');
    }


    /**
     * @param int $id
     * @return void
     */
    public function removeItem(int $id): void
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        $this->session->set('cart', $cart);
    }


    /**
     * @param int $id
     * @return void
     */
    public function decreaseItem(int $id): void
    {
        $cart = $this->session->get('cart', []);
        if ($cart[$id] < 2) {
            unset($cart[$id]);
        } else {
            $cart[$id]--;
        }
        $this->session->set('cart', $cart);
    }


    /**
     * @return array
     */
    public function getDetails(): array
    {
        $cartProducts = [
            'products' => [],
            'totals' => [
                'quantity' => 0,
                'price' => 0,
            ],
        ];

        $cart = $this->session->get('cart', []);
        if ($cart) {
            foreach ($cart as $id => $quantity) {
                $currentProduct = $this->repository->find($id);
                if ($currentProduct) {
                    $cartProducts['products'][] = [
                        'product' => $currentProduct,
                        'quantity' => $quantity
                    ];
                    $cartProducts['totals']['quantity'] += $quantity;
                    $cartProducts['totals']['price'] += $quantity * $currentProduct->getPrice();
                }
            }
        }
        return $cartProducts;
    }
}