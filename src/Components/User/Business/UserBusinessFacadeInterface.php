<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

interface UserBusinessFacadeInterface
{
    public function fetchUserInformationFromAuthentication(Request $request): ResponseDTO;
    public function fetchUserEntityFromAuthentication(Request $request) : ResponseDTO;

}