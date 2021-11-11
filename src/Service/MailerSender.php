<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

final class MailerSender
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailValidation(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from('contact@aurelienecalle.fr')
            ->to($user->getEmail())
            ->subject('SnowTricks - Verify your email')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'token' => $user->getToken(),
            ])
        ;
        $this->mailer->send($email);
    }

    public function sendEmailResetPassword(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from('contact@aurelienecalle.fr')
            ->to($user->getEmail())
            ->subject('SnowTricks - Reset your passwords')
            ->htmlTemplate('emails/reset_password.html.twig')
            ->context([
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'token' => $user->getToken(),
            ])
        ;
        $this->mailer->send($email);
    }
}
