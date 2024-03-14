<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\Components\User\Business\Model\UserRead;
use App\Global\DTO\ResponseDTO;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;

class UserBusinessFacade implements UserBusinessFacadeInterface
{
    public function __construct
    (
        private readonly UserRead $userRead
    )
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function fetchUserInformationFromAuthentication(Request $request): ResponseDTO
    {
        return $this->userRead->fetchUserInformationFromAuthentication($request);
    }
}