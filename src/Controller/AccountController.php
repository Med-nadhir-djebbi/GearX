<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\ChangePasswordType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Espace membre (sécurisé)
 */
class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
        ]);
    }

    /**
     * Permet la modification du mot de passe d'un utilisateur sur une page dédiée
     */
    #[Route('/account/password', name: 'account_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();
            $new_password = $form->get('new_password')->getData();
            $isOldPasswordValid = $passwordHasher->isPasswordValid($user, $old_password);
            if ($isOldPasswordValid) {
                $password = $passwordHasher->hashPassword($user,$new_password);
                $user->setPassword($password);
                $em->flush();
                $this->addFlash(
                    'notice', 
                    'Password updated successfully! Please log in again with your new password.'
                );
                return $this->redirectToRoute('account');
            } else {
                $this->addFlash(
                    'notice', 
                    'Current password is incorrect. Please try again.'
                );
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Affiche la vue de toutes les commandes d'un utilisateur
     */
    #[Route('/account/commandes', name: 'account_orders')]
    public function showOrders(OrderRepository $repository): Response
    {
        $orders = $repository->findPaidOrdersByUser($this->getUser());
        return $this->render('account/orders.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * Affiche une commande
     */
    #[Route('/account/commandes/{reference}', name: 'account_order')]
    public function showOrder(string $reference, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy(['reference' => $reference, 'user' => $this->getUser()]);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        return $this->render('account/order.html.twig', [
            'order' => $order
        ]);
    }
}
