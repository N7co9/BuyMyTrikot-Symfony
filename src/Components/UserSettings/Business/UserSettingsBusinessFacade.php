<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business;

use App\Components\User\Business\Model\SessionManager;
use App\Components\UserSettings\Business\Model\BillingAddressModificationHandling;
use App\Components\UserSettings\Business\Model\PasswordModificationHandling;
use App\Components\UserSettings\Business\Model\UsernameModificationHandling;
use App\Components\UserSettings\Business\Model\VerificationMailHandling;
use App\Global\DTO\BillingDTO;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class UserSettingsBusinessFacade implements UserSettingsBusinessFacadeInterface
{
    public function __construct
    (
        private readonly VerificationMailHandling           $verificationMailHandling,
        private readonly RouterInterface                    $router,
        private readonly SessionManager                     $sessionManager,
        private readonly PasswordModificationHandling       $passwordModificationHandling,
        private readonly UsernameModificationHandling       $usernameModificationHandling,
        private readonly BillingAddressModificationHandling $billingAddressModificationHandling
    )
    {
    }

    public function sendVerificationEmail(Request $request): void
    {
       $this->verificationMailHandling->sendVerificationEmail($this->router, $request);
    }


    public function receiveAndPersistNewEmail(Request $request) : ResponseDTO
    {
        return $this->verificationMailHandling->receiveAndPersistNewEmail($request);
    }
    public function addUnverifiedEmailToSession(Request $request): void
    {
        $this->sessionManager->addNewEmailToSession($request);
    }

    public function setNewPassword(Request $request): ResponseDTO
    {
        return $this->passwordModificationHandling->setNewPassword($request);
    }

    public function setNewUsername(Request $request): ResponseDTO
    {
        return $this->usernameModificationHandling->setNewUsername($request);
    }

    public function setNewBillingAddress(Request $request): array
    {
        return $this->billingAddressModificationHandling->setNewBillingAddress($request);
    }

    public function retrieveBillingAddress(Request $request): ?BillingDTO
    {
        return $this->billingAddressModificationHandling->retrieveBillingAddress($request);
    }

}