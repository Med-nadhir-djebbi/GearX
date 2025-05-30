<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Security\LoginAuthenticator;
use App\Service\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $em): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);
            
            // Set default role
            $user->setRoles(['ROLE_USER']);

            // Save the user
            $em->persist($user);
            $em->flush();

            // Send welcome email
            $content = "Hello {$user->getUsername()}, thank you for joining GearX! Your gaming gear journey starts now.";
            (new Mail)->send($user->getEmail(), $user->getUsername(), "Welcome to GearX - Your Gaming Gear Destination", $content);

            // Add a flash message
            $this->addFlash('success', 'Registration successful! Welcome to GearX.');

            // Authenticate and redirect
            $response = $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

            if ($response) {
                return $response;
            }

            // Fallback redirect if authentication response is null
            return $this->redirectToRoute('account');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
