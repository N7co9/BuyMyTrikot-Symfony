<?php

namespace App\Global\Persistence\DTO;

class UserDTO
{
    public function __construct(
        public readonly int    $id = 0,
        public readonly string $email = '',
        public readonly string $username = '',
        public readonly string $password = ''
    )
    {
    }

}