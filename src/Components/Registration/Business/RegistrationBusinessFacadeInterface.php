<?php

namespace App\Components\Registration\Business;

use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

interface RegistrationBusinessFacadeInterface
{
    /**
     * Register a user with the provided UserDTO.
     *
     * @param Request $request
     * @return array
     */
    public function register(Request $request): ResponseDTO;
}
