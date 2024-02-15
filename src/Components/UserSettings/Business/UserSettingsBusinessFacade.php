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

    public function sendVerificationEmail(UserDTO $userDTO): void
    {
        $this->verificationMailHandling->sendVerificationEmail($this->router, $userDTO);
    }

    public function verifyToken(string $token, Request $request): bool
    {
        return $this->verificationMailHandling->verifyToken($request, $token);
    }

    public function addUnverifiedEmailToSession(Request $request): void
    {
        $this->sessionManager->addNewEmailToSession($request);
    }

    public function setNewPassword(UserDTO $userDTO, Request $request): ResponseDTO
    {
        return $this->passwordModificationHandling->setNewPassword($userDTO, $request);
    }

    public function setNewUsername(Request $request, UserDTO $userDTO): ResponseDTO
    {
        return $this->usernameModificationHandling->setNewUsername($request, $userDTO);
    }

    public function setNewBillingAddress(Request $request, UserDTO $userDTO): array
    {
        return $this->billingAddressModificationHandling->setNewBillingAddress($request, $userDTO);
    }

    public function retrieveBillingAddress(UserDTO $userDTO): ?BillingDTO
    {
        return $this->billingAddressModificationHandling->retrieveBillingAddress($userDTO);
    }

}