<?php

namespace App\Components\Registration\Business\Validation;

use App\Global\Persistence\DTO\ResponseDTO;
use App\Global\Persistence\DTO\UserDTO;

class UserRegistrationValidation
{
    public function validate(UserDTO $userDTO): array
    {
        $errorDTOList = [];
        $this->validateName($userDTO, $errorDTOList);
        $this->validateEmail($userDTO, $errorDTOList);
        $this->validatePassword($userDTO, $errorDTOList);

        return $errorDTOList;
    }

    private function validateName($clientDTO, &$errorDTOList) : void
    {
        if (empty($clientDTO->username) || !preg_match("/^[a-zA-Z-' ]*$/", $clientDTO->username)) {
            $errorDTOList[] = new ResponseDTO('Oops, your name doesn\'t look right', 'Exception');
        }
    }

    private function validateEmail($clientDTO, &$errorDTOList) : void
    {
        if (empty($clientDTO->email) || !filter_var($clientDTO->email, FILTER_VALIDATE_EMAIL)) {
            $errorDTOList[] = new ResponseDTO('Oops, your email doesn\'t look right', 'Exception');
        }
    }

    private function validatePassword($clientDTO, &$errorDTOList) : void
    {
        if (
            empty($clientDTO->password) ||
            !preg_match('@[A-Z]@', $clientDTO->password) ||
            !preg_match('@[a-z]@', $clientDTO->password) ||
            !preg_match('@\d@', $clientDTO->password) ||
            !preg_match('@\W@', $clientDTO->password) ||
            (strlen($clientDTO->password) <= 6)
        ) {
            $errorDTOList[] = new ResponseDTO('Oops, your password doesn\'t look right!', 'Exception');
        }
    }
}