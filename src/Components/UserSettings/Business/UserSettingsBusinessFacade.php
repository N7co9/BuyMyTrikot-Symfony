<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business;

use App\Components\UserSettings\Business\Model\BillingAddressModification\BillingAddressModificationRead;
use App\Components\UserSettings\Business\Model\BillingAddressModification\BillingAddressModificationWrite;
use App\Components\UserSettings\Business\Model\EmailAddressModification\EmailModificationWrite;
use App\Components\UserSettings\Business\Model\PasswordModification\PasswordModificationWrite;
use App\Components\UserSettings\Business\Model\UsernameModification\UsernameModificationWrite;
use App\Global\DTO\BillingDTO;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class UserSettingsBusinessFacade implements UserSettingsBusinessFacadeInterface
{
    public function __construct
    (
        private readonly RouterInterface                 $router,
        private readonly PasswordModificationWrite       $passwordModificationWrite,
        private readonly UsernameModificationWrite       $usernameModificationWrite,
        private readonly BillingAddressModificationWrite $billingAddressModificationWrite,
        private readonly EmailModificationWrite          $verificationMailWrite,
        private readonly BillingAddressModificationRead  $billingAddressModificationRead,
    )
    {
    }

    public function sendVerificationEmail(Request $request): void
    {
        $this->verificationMailWrite->sendVerificationEmail($this->router, $request);
    }


    public function receiveAndPersistNewEmail(Request $request): ResponseDTO
    {
        return $this->verificationMailWrite->receiveAndPersistNewEmail($request);
    }

    public function setNewPassword(Request $request): ResponseDTO
    {
        return $this->passwordModificationWrite->setNewPassword($request);
    }

    public function setNewUsername(Request $request): ResponseDTO
    {
        return $this->usernameModificationWrite->setNewUsername($request);
    }

    public function setNewBillingAddress(Request $request): ResponseDTO
    {
        return $this->billingAddressModificationWrite->setNewBillingAddress($request);
    }

    public function retrieveBillingAddress(Request $request): ?BillingDTO
    {
        return $this->billingAddressModificationRead->retrieveBillingAddress($request);
    }


}