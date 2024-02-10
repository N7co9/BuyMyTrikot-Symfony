<?php

namespace App\Global\DTO;

class UserDTO
{
    public function __construct(
        public int    $id = 0,
        public string $email = '',
        public string $username = '',
        public string $password = '',
        public string $verificationToken = '',
        public bool   $isVerified = false
    )
    {
    }

}