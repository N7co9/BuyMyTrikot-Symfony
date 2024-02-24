<?php
declare(strict_types=1);

namespace App\Components\Authentication\Business;

use App\Components\Authentication\Business\Model\ApiTokenHandler;
use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Entity\User;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AuthenticationBusinessFacade
{
    public function __construct
    (
        private readonly ApiTokenHandler $apiTokenHandler,
        private readonly ApiTokenRepository $apiTokenRepository
    )
    {}

    public function getUserBadgeFromToken(#[\SensitiveParameter] string $accessToken) : UserBadge
    {
        return $this->apiTokenHandler->getUserBadgeFrom($accessToken);
    }

    public function generateApiToken(User $user) : string
    {
        return $this->apiTokenHandler->generateApiToken($user);
    }

    public function getUserFromToken(string $accessToken) : User
    {
        return $this->apiTokenRepository->findUserByToken($accessToken);
    }
}