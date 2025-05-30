<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private TokenGeneratorInterface $tokenGenerator
    ) {
    }

    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData()
            );
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(Request $request, string $token, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'resetToken' => $token,
        ]);

        if (!$user || !$user->isResetTokenValid()) {
            throw $this->createNotFoundException('Invalid or expired reset password link.');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $token = $this->tokenGenerator->generateToken();
        $user->setResetToken($token);
        $user->setResetTokenExpiresAt(new \DateTime('+1 hour'));

        $this->entityManager->flush();

        $email = (new TemplatedEmail())
            ->from('noreply@gearx.com')
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('email/reset_password.html.twig')
            ->context([
                'user' => $user,
                'resetUrl' => $this->generateUrl('app_reset_password', ['token' => $token], 0),
                'tokenLifetime' => 60,
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('app_check_email');
    }
} 