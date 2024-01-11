<?php

namespace App\Global\Persistence\Mapping;

use App\Entity\User as UserEntity;
use App\Global\Persistence\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

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

    public function request2DTO(Request $request): UserDTO
    {
        return new UserDTO(
            id: $request->get(''),
            email: $request->get('email'),
            username: $request->get('username'),
            password: $request->get('password')
        );
    }

}