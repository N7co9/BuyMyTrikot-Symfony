<?php
declare(strict_types=1);

namespace App\Components\Registration\Business\Model;

use App\Components\Registration\Communication\Mapper\Request2UserDTO;
use App\Components\Registration\Persistence\UserEntityManager;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use Exception;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Request;

class UserRegistrationHandling
{
    public function __construct
    (
        private readonly UserRegistrationValidation $registrationValidation,
        private readonly Request2UserDTO            $request2UserDTO,
        private readonly UserEntityManager          $userEntityManager,
    )
    {
    }

    /**
     * @throws RandomException
     */
    public function register(Request $request): array
    {
        $userDTO = $this->request2UserDTO->request2DTO($request);

        $errors = $this->validate($userDTO);

        if (empty($errors)) {
            try {
                $this->userEntityManager->register($userDTO);
            } catch (Exception) {
                $errors [] = new ResponseDTO('Email is not unique!!!', 'ERROR');
            }
        }
        return $errors;
    }

    private function validate(UserDTO $userDTO): ?array
    {
        return $this->registrationValidation->validate($userDTO);
    }
}