<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Global\DTO\ResponseDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UsernameModificationHandling
{
    public function __construct
    (
        private readonly ApiTokenRepository     $apiTokenRepository,
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

    public function setNewUsername(Request $request): ResponseDTO
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $res = $this->verifyNewUsername($data['username']);

        $user = $this->apiTokenRepository->findUserByToken($request->headers->get('Authorization'));

        if ($user !== null && $res->getType() === 'OK') {
            $user->setUsername($data['username']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return new ResponseDTO('Username changed successfully.', 'OK');
        }
        return $res;
    }
}