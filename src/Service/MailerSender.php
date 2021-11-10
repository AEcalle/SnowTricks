<?php 
namespace App\Service;

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
            ->from('contact@snowtricks.com')
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
}