<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model;

use App\Global\DTO\UserDTO;
use App\Global\Service\MailerInterface\MailService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class VerificationMailHandling
{
    private function generateUrl(RouterInterface $router, UserDTO $userDTO) : string
    {
        $verificationToken = $userDTO->verificationToken;
        return $router->generate('app_email_processing', ['verificationToken' => $verificationToken], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function sendVerificationEmail(RouterInterface $router, UserDTO $userDTO, MailService $mailService, MailerInterface $symfonyMailer) : void
    {
        $verificationUrl = $this->generateUrl($router, $userDTO);
        $mailService->sendVerificationEmail($symfonyMailer, $userDTO, $verificationUrl);
    }
}