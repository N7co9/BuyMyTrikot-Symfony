<?php
declare(strict_types=1);

namespace App\Components\Settings\Persistence;

use App\Entity\TemporaryMails;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class EmailVerificationEntityManager
{
    public function __construct
    (
        private readonly EntityManagerInterface $entityManager
    )
    {
    }
    public function persistNewTemporaryEmail(TemporaryMails $mail) : void
    {
        $this->entityManager->persist($mail);
        $this->entityManager->flush();
    }

    public function persistNewVerifiedEmail(User $newUserEntity) : void
    {
        $this->entityManager->persist($newUserEntity);
        $this->entityManager->flush();
    }
}