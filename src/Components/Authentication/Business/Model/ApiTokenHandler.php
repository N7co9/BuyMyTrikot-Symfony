<?php
declare(strict_types=1);

namespace App\Components\Authentication\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenEntityManager;
use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Entity\ApiToken;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class ApiTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct
    (
        private readonly ApiTokenRepository $apiTokenRepository,
        private readonly ApiTokenEntityManager $tokenEntityManager
    )
    {
    }
    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $token = $this->apiTokenRepository->findOneBy(['token' => $accessToken]);
        if (!$token) {
            throw new BadCredentialsException();
        }
        $this->validateToken($token);
        return new UserBadge($token->getOwnedBy()?->getUserIdentifier());
    }

    private function validateToken(ApiToken $token): void
    {
        $currentDateTime = new \DateTimeImmutable();

        if ($token->getExpiresAt() && $token->getExpiresAt() < $currentDateTime) {
            throw new BadCredentialsException('Token has expired.');
        }
    }


    public function generateApiToken(User $user) : string
    {
        $alreadyExistingApiToken = $this->apiTokenRepository->findMostRecentEntityByUserId($user->getId());


        if($alreadyExistingApiToken)
        {
            try {
                $this->validateToken($alreadyExistingApiToken);
            }
            catch (BadCredentialsException)
            {
                $apiToken = new ApiToken();
                $apiToken->setOwnedBy($user);
                $this->tokenEntityManager->saveApiToken($apiToken);
                return $apiToken->getToken();
            }
            return $alreadyExistingApiToken->getToken();
        }

        $apiToken = new ApiToken();
        $apiToken->setOwnedBy($user);

        $this->tokenEntityManager->saveApiToken($apiToken);

        return $apiToken->getToken();
    }

}