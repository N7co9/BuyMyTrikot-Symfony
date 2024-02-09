<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business;

use App\Components\UserSettings\Business\Model\VerificationMailHandling;
use App\Global\DTO\UserDTO;
use App\Global\Service\MailerInterface\MailService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;

class UserSettingsBusinessFacade
{
    public function __construct
    (
        private readonly VerificationMailHandling $verificationMailHandling,
        private readonly RouterInterface $router,
        private readonly MailerInterface $symfonyMailer,
        private readonly MailService $mailService,
    )
    {
    }

    public function sendVerificationEmail(UserDTO $userDTO) : void
    {
        $this->verificationMailHandling->sendVerificationEmail($this->router, $userDTO, $this->mailService, $this->symfonyMailer);
    }
}