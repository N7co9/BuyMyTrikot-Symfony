<?php

namespace App\Global\Service\Mapping;

use App\Entity\User as UserEntity;
use App\Global\DTO\UserDTO;

class UserMapper
{
    public function mapEntityToDto(UserEntity $user): UserDTO
    {
        return new UserDTO(
            id: $user->getId(),
            email: $user->getEmail(),
            username: $user->getUsername(),
            verificationToken:  $user->getVerificationToken()
        );
    }
}