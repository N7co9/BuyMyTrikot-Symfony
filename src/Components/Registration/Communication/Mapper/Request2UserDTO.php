<?php
declare(strict_types=1);

namespace App\Components\Registration\Communication\Mapper;

use App\Global\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

class Request2UserDTO
{
    public function request2DTO(Request $request): UserDTO
    {
        $userData = [];
        if($request->getContent())
        {
            $userData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        }

        return new UserDTO(
            id: $request->get('', 0),
            email: $userData['email'] ?? '',
            username: $userData['username'] ?? '',
            password: $userData['password'] ?? '',
            verificationToken: bin2hex(random_bytes(16))
        );
    }
}