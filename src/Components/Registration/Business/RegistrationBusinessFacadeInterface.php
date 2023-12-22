<?php

namespace App\Components\Registration\Business;

use App\Global\Persistence\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

interface RegistrationBusinessFacadeInterface
{
    /**
     * Validate a user DTO.
     *
     * @param UserDTO $userDTO
     * @return array|null
     */
    public function validate(UserDTO $userDTO): ?array;

    /**
     * Map a request to a UserDTO.
     *
     * @param Request $request
     * @return UserDTO
     */
    public function mapRequestToUserDto(Request $request): UserDTO;

    /**
     * Register a user with the provided UserDTO.
     *
     * @param UserDTO $userDTO
     * @return array|null
     */
    public function register(UserDTO $userDTO): ?array;
}
