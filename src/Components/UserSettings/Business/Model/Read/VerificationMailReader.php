<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Business\Model\Read;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Components\UserSettings\Persistence\TemporaryMailsRepository;
use App\Entity\User;
use App\Global\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class VerificationMailReader
{
    public function __construct
    (
        private readonly TemporaryMailsRepository $temporaryMailsRepository,
        private readonly ApiTokenRepository       $apiTokenRepository,
    )
    {
    }

    public function getUserFromToken(Request $request): User
    {
        return $this->apiTokenRepository->findUserByToken($request->headers->get('Authorization'));
    }

    public function generateUrl(RouterInterface $router, UserDTO $userDTO): string
    {
        $verificationToken = $userDTO->verificationToken;
        $route = $router->generate('app_email_processing', ['verificationToken' => $verificationToken], UrlGeneratorInterface::ABSOLUTE_URL);

        $url = 'http://localhost:3000/user/' . parse_url($route, PHP_URL_PATH);
        return $url;
    }

    public function addNewEmailToUserDTO(Request $request, UserDTO $userDTO) : UserDTO
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $userDTO->email = $data['email'];

        return $userDTO;
    }

    public function verifyToken(Request $request): User
    {
        $user = $this->getUserFromToken($request);

        if ($user) {
            $newEmail = $this->temporaryMailsRepository->findEmailByOwnedBy($user->id);

            $user?->setEmail($newEmail);
            $user?->setIsVerified(true);
            $user->setVerificationToken(bin2hex(random_bytes(16)));
        }
        return $user;
    }
}