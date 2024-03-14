<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\Mapping\UserMapper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;

class UserRead
{
    public function __construct
    (
        private readonly ApiTokenRepository $apiTokenRepository,
        private readonly UserMapper         $userMapper
    )
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function fetchUserInformationFromAuthentication(Request $request): ResponseDTO
    {
        $user = $this->apiTokenRepository->findUserByToken($request->headers->get('Authorization'));
        if ($user) {
            return new ResponseDTO($this->userMapper->mapEntityToDto($user), true);
        }
        return new ResponseDTO('An error occurred while fetching the User', false);
    }
}