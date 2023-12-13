<?php

namespace App\Components\Registration\Business;

use App\Components\Registration\Business\Validation\UserRegistrationValidation;
use App\Global\Persistence\DTO\UserDTO;

class RegistrationBusinessFacade
{
    public function __construct(
        public UserRegistrationValidation $registrationValidation
    )
    {}

    public function validate(UserDTO $userDTO) : ?array
    {
        return $this->registrationValidation->validate($userDTO);
    }
}