<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $toEmail, string $toName, string $subject, string $template, array $context = []): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@gearx.com')
            ->to($toEmail)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context(array_merge([
                'recipientName' => $toName
            ], $context));

        $this->mailer->send($email);
    }


} 