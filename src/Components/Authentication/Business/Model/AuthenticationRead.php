<?php
declare(strict_types=1);

namespace App\Components\Authentication\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Global\DTO\ResponseDTO;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AuthenticationRead
{
    public function __construct
    (
        private readonly ApiTokenRepository $apiTokenRepository
    )
    {
    }

    public function getUserFromToken(Request $request): ResponseDTO
    {
        $accessToken = $request->headers->get('Authorization');
        if ($accessToken !== null) {
            $user = $this->apiTokenRepository->findUserByToken($accessToken);
            return new ResponseDTO($user, true);
        }
        return new ResponseDTO('No Token provided', false);
    }

    public function validateToken(ApiToken $token): void
    {
        $currentDateTime = new DateTimeImmutable();

        if ($token->getExpiresAt() && $token->getExpiresAt() < $currentDateTime) {
            throw new BadCredentialsException('Token has expired.');
        }
    }

    public function getMostRecentApiTokenEntity(User $user): ?ApiToken
    {
        return $this->apiTokenRepository->findMostRecentEntityByUserId($user->id);
    }

}