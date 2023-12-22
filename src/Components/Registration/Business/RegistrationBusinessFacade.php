<?php

namespace App\Components\Registration\Business;

use App\Components\Registration\Business\Validation\UserRegistrationValidation;
use App\Components\Registration\Persistence\UserEntityManager;
use App\Global\Persistence\DTO\ResponseDTO;
use App\Global\Persistence\DTO\UserDTO;
use App\Global\Persistence\Mapping\Mapper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;

class RegistrationBusinessFacade implements RegistrationBusinessFacadeInterface
{
    public function __construct(
        public UserRegistrationValidation $registrationValidation,
        public Mapper $mapper,
        public UserEntityManager $entityManager
    )
    {}

    public function validate(UserDTO $userDTO) : ?array
    {
        return $this->registrationValidation->validate($userDTO);
    }

    public function mapRequestToUserDto(Request $request): UserDTO
    {
        return $this->mapper->mapRequest2DTO($request);
    }

    public function register(UserDTO $userDTO) : ?array
    {
        try {
            $this->entityManager->register($userDTO);
        } catch (\Exception) {
            return [
                new ResponseDTO('Email is not unique!!!', 'ERROR')
            ];
        }
        return null;
    }
}