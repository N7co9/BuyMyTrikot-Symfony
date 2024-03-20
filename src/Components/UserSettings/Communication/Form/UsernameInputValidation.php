<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Communication\Form;

use App\Global\DTO\ResponseDTO;

class UsernameInputValidation
{
    public function __construct()
    {
    }

    public function validateNewUsername(string $newUsername): ResponseDTO
    {
        $usernameLength = strlen($newUsername);

        if ($usernameLength < 3 || $usernameLength > 20) {
            return new ResponseDTO('Username must be between 3 and 20 characters long', false);
        }
        if (!preg_match("/^[a-zA-Z0-9_.]*$/", $newUsername)) {
            return new ResponseDTO('Oops, your name doesn\'t meet our requirements. Only letters, numbers, underscores, and dots are allowed.', false);
        }
        return new ResponseDTO($newUsername, true);
    }
}