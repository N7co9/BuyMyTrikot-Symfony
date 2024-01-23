<?php

namespace App\Components\Authentication\Business;

use App\Components\Authentication\Business\Model\UserLoginValidation;
use App\Global\Persistence\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationBusinessFacade implements AuthenticationBusinessFacadeInterface
{
    public function __construct(
        public UserLoginValidation $loginValidation
    )
    {
    }

    public function authenticate(array $formData, SessionInterface $session): ?ResponseDTO
    {
        return $this->loginValidation->authenticate($formData, $session);
    }
}