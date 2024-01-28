<?php

namespace App\Global\Persistence\Mapping;

use App\Entity\User as UserEntity;
use App\Global\Persistence\DTO\UserDTO;

class UserMapper
{
    public function mapEntityToDto(UserEntity $user): UserDTO
    {
        return new UserDTO(
            id: $user->getId(),
            email: $user->getEmail(),
            username: $user->getUsername(),
        );
    }
}