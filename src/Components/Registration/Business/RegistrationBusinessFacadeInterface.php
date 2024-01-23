<?php

namespace App\Components\Registration\Business;

use App\Global\Persistence\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

interface RegistrationBusinessFacadeInterface
{
    /**
     * Register a user with the provided UserDTO.
     *
     * @param Request $request
     * @return array
     */
    public function register(Request $request): array;
}
