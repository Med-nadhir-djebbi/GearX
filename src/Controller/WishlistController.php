<?php

namespace App\Controller;

use App\Model\Wishlist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'wishlist')]
    public function index(Wishlist $wishlist): Response
    {
        $wishlistProducts = $wishlist->getDetails();

        return $this->render('wishlist/index.html.twig', [
            'wishlist' => $wishlistProducts['products'],
            'totalQuantity' => $wishlistProducts['totals']['quantity'],
            'totalPrice' => $wishlistProducts['totals']['price'],
        ]);
    }

    #[Route('/wishlist/add/{id}', name: 'add_to_wishlist')]
    public function add(Wishlist $wishlist, int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository('App\Entity\Product')->find($id);
        
        if (!$product) {
            $this->addFlash('danger', 'Product not found');
            return $this->redirectToRoute('product');
        }
        
        // Add to wishlist even if out of stock, since it's just a wishlist
        $wishlist->add($id);
        
        $this->addFlash('success', 'Item added to your wishlist!');

        // Get referer URL to redirect back to the page where the user clicked
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $referer = $request->headers->get('referer');
        
        // If referer exists, redirect to it, else redirect to wishlist page
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('wishlist');
    }

    #[Route('/wishlist/remove/{id}', name: 'remove_wishlist_item')]
    public function remove(Wishlist $wishlist, int $id): Response
    {
        $wishlist->remove($id);
        
        $this->addFlash('success', 'Item removed from your wishlist');

        return $this->redirectToRoute('wishlist');
    }

    #[Route('/wishlist/clear', name: 'clear_wishlist')]
    public function clear(Wishlist $wishlist): Response
    {
        $wishlist->clear();
        
        $this->addFlash('success', 'Your wishlist has been cleared');

        return $this->redirectToRoute('wishlist');
    }

    #[Route('/wishlist/add-to-cart/{id}', name: 'wishlist_to_cart')]
    public function addToCart(Wishlist $wishlist, int $id, EntityManagerInterface $entityManager): Response
    {
        // Check if product is in stock before moving to cart
        $product = $entityManager->getRepository('App\Entity\Product')->find($id);
        
        if (!$product) {
            $this->addFlash('danger', 'Product not found');
            return $this->redirectToRoute('wishlist');
        }
        
        // Check if product is in stock
        if ($product->getStock() <= 0) {
            $this->addFlash('danger', 'This product is out of stock and cannot be added to your cart');
            return $this->redirectToRoute('wishlist');
        }
        
        // Remove from wishlist
        $wishlist->remove($id);
        
        $this->addFlash('success', 'Item moved to your cart');
        
        // Redirect to add_to_cart route
        return $this->redirectToRoute('add_to_cart', ['id' => $id]);
    }
}
