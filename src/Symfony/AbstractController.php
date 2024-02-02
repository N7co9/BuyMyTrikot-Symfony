<?php

namespace App\Symfony;

use App\Entity\User as UserEntity;
use App\Global\Persistence\DTO\UserDTO;
use App\Global\Persistence\Mapping\UserMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

abstract class AbstractController extends SymfonyAbstractController
{
    private ?UserDTO $userDto = null;

    public function getLoggingUser(): UserDTO
    {
        if ($this->userDto instanceof UserDTO) {
            return $this->userDto;
        }

        $user = $this->getUser();
        if (!$user instanceof UserEntity) {
            throw $this->createNotFoundException('User not authenticated.');
        }

        return (new UserMapper())->mapEntityToDto($user);
    }

    public function setLoggingUser(UserDTO $userDto): void
    {
        $this->userDto = $userDto;
    }
}