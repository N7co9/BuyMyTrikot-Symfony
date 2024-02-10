<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model;

use App\Components\User\Persistence\UserRepository;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\Mapping\Mapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PasswordModificationHandling
{
    public function __construct
    (
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository
    )
    {
    }

    private function checkInputPassword(UserDTO $userDTO, string $formInputOldPassword): bool
    {
        $hashedPassword = $userDTO->password;
        return password_verify($formInputOldPassword, $hashedPassword);
    }

    public function setNewPassword(UserDTO $userDTO, Request $request): ResponseDTO
    {
        $formInputOldPassword = $request->request->get('current_password');
        $formInputNewPassword = $request->request->get('new_password');
        $isOldPasswordCorrect = $this->checkInputPassword($userDTO, $formInputOldPassword);

        $user = $this->userRepository->findOneByEmail($userDTO->email);

        if ($isOldPasswordCorrect && $user !== null) {
            $response = $this->verifyNewPassword($formInputNewPassword);
            if ($response->getType() === 'OK') {
                $user->setPassword(password_hash($formInputNewPassword, PASSWORD_DEFAULT));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return new ResponseDTO('Password changed successfully.', 'OK');
            }
            return $response;
        }
        return new ResponseDTO('Your current Password is incorrect. Please try again.', 'Exception');
    }

    private function verifyNewPassword($formInputPassword): ResponseDTO
    {
        if (
            empty($formInputPassword) ||
            !preg_match('@[A-Z]@', $formInputPassword) ||
            !preg_match('@[a-z]@', $formInputPassword) ||
            !preg_match('@\d@', $formInputPassword) ||
            !preg_match('@\W@', $formInputPassword) ||
            (strlen($formInputPassword) <= 6)
        ) {
            return new ResponseDTO('Your new Password doesn\'t match the requirements.', 'Exception');
        }
        return new ResponseDTO('', 'OK');
    }

}