<?php

namespace App\Model;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class Wishlist
{
    private $session;
    private $repository;

    public function __construct(RequestStack $requestStack, ProductRepository $repository)
    {
        $this->session = $requestStack->getSession();
        $this->repository = $repository;
    }

    public function add(int $id): void
    {
        $wishlist = $this->session->get('wishlist', []);

        if (!empty($wishlist[$id])) {
            // Item already in wishlist, don't do anything
            return;
        } else {
            // Add the item to wishlist
            $wishlist[$id] = 1;
        }

        $this->session->set('wishlist', $wishlist);
    }

    public function get()
    {
        $wishlistItems = [];
        $wishlist = $this->session->get('wishlist', []);

        foreach ($wishlist as $id => $quantity) {
            $product = $this->repository->find($id);
            if ($product) {
                $wishlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $wishlistItems;
    }

    public function remove(int $id): void
    {
        $wishlist = $this->session->get('wishlist', []);

        if (!empty($wishlist[$id])) {
            unset($wishlist[$id]);
        }

        $this->session->set('wishlist', $wishlist);
    }

    public function clear(): void
    {
        $this->session->set('wishlist', []);
    }

    public function getDetails(): array
    {
        $wishlistProducts = [
            'products' => [],
            'totals' => [
                'quantity' => 0,
                'price' => 0,
            ],
        ];

        $wishlist = $this->session->get('wishlist', []);
        if ($wishlist) {
            foreach ($wishlist as $id => $quantity) {
                $currentProduct = $this->repository->find($id);
                if ($currentProduct) {
                    $wishlistProducts['products'][] = [
                        'product' => $currentProduct,
                        'quantity' => $quantity
                    ];
                    $wishlistProducts['totals']['quantity'] += $quantity;
                    $wishlistProducts['totals']['price'] += $quantity * $currentProduct->getPrice();
                }
            }
        }
        return $wishlistProducts;
    }
}
