<?php

namespace App\Controller;

use App\Model\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * 
     * @param Cart $cart
     * @return Response
     */
    #[Route('/mycart', name: 'cart')]
    public function index(Cart $cart): Response
    {
        $cartProducts = $cart->getDetails();

        return $this->render('cart/index.html.twig', [
            'cart' => $cartProducts['products'],
            'totalQuantity' => $cartProducts['totals']['quantity'],
            'totalPrice' =>$cartProducts['totals']['price']
        ]);
    }

    /**
     * @param Cart $cart
     * @param int $id
     * @return Repsonse
     */
    #[Route('/cart/add/{id}/{quantity?1}', name: 'add_to_cart')]
    public function add(Cart $cart, int $id, int $quantity = 1): Response
    {
        $cart->add($id, $quantity);
        return $this->redirectToRoute('cart');
    }

    /**
     * @param Cart $cart
     * @param int $id
     * @return Repsonse
     */
    #[Route('/cart/decrease/{id}', name: 'decrease_item')]
    public function decrease(Cart $cart, int $id): Response
    {
        $cart->decreaseItem($id);
        return $this->redirectToRoute('cart');
    }
    
    /**
     *
     * @param Cart $cart
     * @return Response
     */
    #[Route('/cart/remove/{id}', name: 'remove_cart_item')]
    public function removeItem(Cart $cart, int $id): Response
    {
        $cart->removeItem($id);
        return $this->redirectToRoute('cart');
    }

    /**
     *
     * @param Cart $cart
     * @return Response
     */
    #[Route('/cart/remove/', name: 'remove_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('product');
    }
}
