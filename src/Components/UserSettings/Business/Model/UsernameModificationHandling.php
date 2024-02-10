<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model;

use App\Components\User\Persistence\UserRepository;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UsernameModificationHandling
{
    public function __construct
    (
        private readonly UserRepository         $userRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    private function verifyNewUsername(string $newUsername): ResponseDTO
    {
        $usernameLength = strlen($newUsername);

        if ($usernameLength < 3 || $usernameLength > 20) {
            return new ResponseDTO('Username must be between 3 and 20 characters long', 'Exception');
        }
        if (!preg_match("/^[a-zA-Z0-9_.]*$/", $newUsername)) {
            return new ResponseDTO('Oops, your name doesn\'t meet our requirements. Only letters, numbers, underscores, and dots are allowed.', 'Exception');
        }
        return new ResponseDTO('', 'OK');
    }

    public function setNewUsername(Request $request, UserDTO $userDTO): ResponseDTO
    {
        $newUsername = $request->request->get('username');
        $res = $this->verifyNewUsername($newUsername);
        $user = $this->userRepository->findOneByEmail($userDTO->email);

        if ($user !== null && $res->getType() === 'OK') {
            $user->setUsername($newUsername);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return new ResponseDTO('Username changed successfully.', 'OK');
        }
        return $res;
    }
}