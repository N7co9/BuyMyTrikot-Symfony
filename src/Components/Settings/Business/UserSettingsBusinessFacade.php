<?php
declare(strict_types=1);

namespace App\Components\Settings\Business;

use App\Components\Settings\Business\Model\EmailAddressModification\VerificationMailHandling;
use App\Components\Settings\Business\Model\BillingAddressModification\BillingAddressModificationHandling;
use App\Components\Settings\Business\Model\PasswordModification\PasswordModificationHandling;
use App\Components\Settings\Business\Model\UsernameModification\UsernameModificationWrite;
use App\Global\DTO\BillingDTO;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class UserSettingsBusinessFacade implements UserSettingsBusinessFacadeInterface
{
    public function __construct
    (
        private readonly VerificationMailHandling           $verificationMailHandling,
        private readonly RouterInterface                    $router,
        private readonly PasswordModificationHandling       $passwordModificationHandling,
        private readonly UsernameModificationWrite          $usernameModificationWrite,
        private readonly BillingAddressModificationHandling $billingAddressModificationHandling
    )
    {
    }

    public function sendVerificationEmail(Request $request): void
    {
        $this->verificationMailHandling->sendVerificationEmail($this->router, $request);
    }


    public function receiveAndPersistNewEmail(Request $request): ResponseDTO
    {
        return $this->verificationMailHandling->receiveAndPersistNewEmail($request);
    }

    public function setNewPassword(Request $request): ResponseDTO
    {
        return $this->passwordModificationHandling->setNewPassword($request);
    }

    public function setNewUsername(Request $request): ResponseDTO
    {
        return $this->usernameModificationWrite->setNewUsername($request);
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