<?php
declare(strict_types=1);

namespace App\Components\Settings\Business;

use App\Global\DTO\BillingDTO;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

interface UserSettingsBusinessFacadeInterface
{
    public function sendVerificationEmail(Request $request): void;

    public function receiveAndPersistNewEmail(Request $request) : ResponseDTO;

    public function setNewPassword(Request $request): ResponseDTO;

    public function setNewUsername(Request $request): ResponseDTO;

    public function setNewBillingAddress(Request $request): array;

    public function retrieveBillingAddress(Request $request): ?BillingDTO;

}