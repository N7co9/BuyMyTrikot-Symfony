<?php
declare(strict_types=1);

namespace App\Components\Authentication\Business;

use App\Components\Authentication\Business\Model\AuthenticationRead;
use App\Components\Authentication\Business\Model\AuthenticationWrite;
use App\Entity\User;
use App\Global\DTO\ResponseDTO;
use SensitiveParameter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AuthenticationBusinessFacade implements AuthenticationBusinessFacadeInterface
{
    public function __construct
    (
        private readonly AuthenticationWrite $apiTokenHandler,
        private readonly AuthenticationRead  $authenticationReader
    )
    {
    }

    public function getUserBadgeFromToken(#[SensitiveParameter] string $accessToken): UserBadge
    {
        return $this->apiTokenHandler->getUserBadgeFrom($accessToken);
    }

    public function generateApiToken(User $user): string
    {
        return $this->apiTokenHandler->generateApiToken($user);
    }

    public function getUserFromToken(Request $request): ResponseDTO
    {
        return $this->authenticationReader->getUserFromToken($request);
    }

}