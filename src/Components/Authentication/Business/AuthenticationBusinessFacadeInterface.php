<?php
declare(strict_types=1);

namespace App\Components\Authentication\Business;

use App\Entity\User;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

interface AuthenticationBusinessFacadeInterface
{
    public function getUserBadgeFromToken(#[\SensitiveParameter] string $accessToken): UserBadge;

    public function generateApiToken(User $user): string;

    public function getUserFromToken(string $accessToken): User;
}