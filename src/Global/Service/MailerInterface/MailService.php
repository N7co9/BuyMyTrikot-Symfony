<?php
declare(strict_types=1);

namespace App\Global\Service\MailerInterface;

use App\Global\DTO\UserDTO;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    public function sendVerificationEmail(MailerInterface $mailer, UserDTO $user, string $verificationUrl) : void
    {
        $email = (new TemplatedEmail())
            ->from('nico.gruenewald@cec.valantic.com')
            ->to($user->email)
            ->subject('Email Verification')
            ->htmlTemplate('email/verification_email.html.twig')
            ->context([
                'user' => $user,
                'verificationUrl' => $verificationUrl,
            ]);

        $mailer->send($email);
    }
}