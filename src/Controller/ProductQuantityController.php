<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductQuantityController extends AbstractController
{
    #[Route('/product/{id}/quantity/{action?}', name: 'update_product_quantity', methods: ['POST'])]
    public function updateQuantity(
        Request $request,
        int $id,
        ?string $action,
        ProductRepository $productRepository
    ): Response {
        $product = $productRepository->find($id);
        
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $session = $request->getSession();
        $currentQuantity = $session->get('quantity_' . $id, 1);

        if ($action === 'increase' && $currentQuantity < $product->getStock()) {
            $session->set('quantity_' . $id, $currentQuantity + 1);
        } elseif ($action === 'decrease' && $currentQuantity > 1) {
            $session->set('quantity_' . $id, $currentQuantity - 1);
        } elseif (!$action) {
            $quantity = (int) $request->request->get('quantity', 1);
            $quantity = max(1, min($quantity, $product->getStock()));
            $session->set('quantity_' . $id, $quantity);
        }

        // If it's an AJAX request, return JSON response
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
                'quantity' => $session->get('quantity_' . $id, 1)
            ]);
        }

        // If it's a regular request, redirect back to the product page
        return $this->redirectToRoute('product_show', ['slug' => $id]);
    }
}
