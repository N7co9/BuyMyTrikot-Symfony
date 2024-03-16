<?php
declare(strict_types=1);

namespace App\Components\Settings\Persistence;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UsernameEntityManager
{
    public function __construct
    (
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function persistNewUsername(User $modifiedUserEntity): void
    {
        $this->entityManager->persist($modifiedUserEntity);
        $this->entityManager->flush();
    }
}