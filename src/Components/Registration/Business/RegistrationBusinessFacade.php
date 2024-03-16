<?php

namespace App\Components\Registration\Business;

use App\Components\Registration\Business\Model\UserRegistrationHandling;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

class RegistrationBusinessFacade implements RegistrationBusinessFacadeInterface
{
    public function __construct(
        private readonly UserRegistrationHandling $userRegistrationHandling,
    )
    {
    }

    public function register(Request $request): ResponseDTO
    {
        return $this->userRegistrationHandling->register($request);
    }
}