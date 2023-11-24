<?php

namespace App\Model\EntityManager;

use App\Entity\User;
use App\Model\DTO\UserDTO;
use App\Service\Mapping\Mapper;
use Doctrine\ORM\EntityManagerInterface;

class UserEntityManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Mapper $mapper,
    )
    {}

    public function register(UserDTO $userDTO): void
    {
        $user = $this->mapper->getUserEntity($userDTO);
        $user->setPassword($this->hashPassword($user));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    private function hashPassword(User $user) : string
    {
       return password_hash($user->getPassword(), PASSWORD_DEFAULT);
    }
}