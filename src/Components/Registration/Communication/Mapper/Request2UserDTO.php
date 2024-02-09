<?php
declare(strict_types=1);

namespace App\Components\Registration\Communication\Mapper;

use App\Global\DTO\UserDTO;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Request;

class Request2UserDTO
{
    /**
     * @throws RandomException
     */
    public function request2DTO(Request $request): UserDTO
    {
        return new UserDTO(
            id: $request->get('', 0),
            email: $request->get('email', ''),
            username: $request->get('username', ''),
            password: $request->get('password', ''),
            verificationToken: bin2hex(random_bytes(16))
        );
    }
}