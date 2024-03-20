<?php
declare(strict_types=1);

namespace App\Components\Authentication\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenEntityManager;
use App\Entity\ApiToken;
use App\Entity\User;
use SensitiveParameter;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AuthenticationWrite implements AccessTokenHandlerInterface
{
    public function __construct
    (
        private readonly ApiTokenEntityManager $tokenEntityManager,
        private readonly AuthenticationRead    $authenticationReader
    )
    {
    }


    public function generateApiToken(User $user): string
    {
        $alreadyExistingApiToken = $this->authenticationReader->getMostRecentApiTokenEntity($user);
        if ($alreadyExistingApiToken) {
            try {
                $this->authenticationReader->validateToken($alreadyExistingApiToken);
            } catch (BadCredentialsException) {
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

    public function getUserBadgeFrom(#[SensitiveParameter] string $accessToken): UserBadge
    {
        return new UserBadge('');
    }
}