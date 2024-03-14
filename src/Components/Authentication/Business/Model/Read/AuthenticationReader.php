<?php
declare(strict_types=1);

namespace App\Components\Authentication\Business\Model\Read;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Entity\User;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationReader
{
    public function __construct
    (
        private readonly ApiTokenRepository $apiTokenRepository
    )
    {
    }
    public function getUserFromToken(Request $request) : ResponseDTO
    {
        $accessToken = $request->headers->get('Authorization');
        if ($accessToken !== null) {
            return $this->apiTokenRepository->findUserByToken($accessToken);
        }
    }
}