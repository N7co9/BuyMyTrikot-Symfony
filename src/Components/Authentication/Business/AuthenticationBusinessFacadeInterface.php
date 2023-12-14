<?php

namespace App\Components\Authentication\Business;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface AuthenticationBusinessFacadeInterface
{
    public function authenticate(array $formData, SessionInterface $session);
}