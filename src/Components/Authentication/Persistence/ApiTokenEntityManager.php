<?php
declare(strict_types=1);

namespace App\Components\Authentication\Persistence;

use App\Entity\ApiToken;
use Doctrine\ORM\EntityManagerInterface;

class ApiTokenEntityManager
{
    public function __construct
    (
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function saveApiToken(ApiToken $token): void
    {
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}