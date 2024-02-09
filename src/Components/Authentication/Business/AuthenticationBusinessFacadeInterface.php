<?php

namespace App\Components\Authentication\Business;

use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface AuthenticationBusinessFacadeInterface
{
    /**
     * Authenticate a user.
     *
     * @param array $formData
     * @param SessionInterface $session
     * @return ResponseDTO|null
     */
    public function authenticate(array $formData, SessionInterface $session): ?ResponseDTO;
}