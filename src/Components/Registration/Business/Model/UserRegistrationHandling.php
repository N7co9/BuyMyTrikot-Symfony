<?php
declare(strict_types=1);

namespace App\Components\Registration\Business\Model;

use App\Components\Registration\Communication\Mapper\Request2UserDTO;
use App\Components\Registration\Persistence\RegistrationEntityManager;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use Exception;
use JsonException;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Request;

class UserRegistrationHandling
{
    public function __construct
    (
        private readonly UserRegistrationValidation $registrationValidation,
        private readonly Request2UserDTO            $request2UserDTO,
        private readonly RegistrationEntityManager  $userEntityManager,
    )
    {
    }

    /**
     * @param Request $request
     * @return ResponseDTO|null
     * @throws JsonException
     */
    public function register(Request $request): ?ResponseDTO
    {
        $userDTO = $this->request2UserDTO->request2DTO($request);
        $formValidationExceptions = $this->validate($userDTO);

        if (empty($formValidationExceptions)) {
            return $this->userEntityManager->register($userDTO);
        }

        return new ResponseDTO($formValidationExceptions, false);
    }


    private function validate(UserDTO $userDTO): ?array
    {
        return $this->registrationValidation->validate($userDTO);
    }
}