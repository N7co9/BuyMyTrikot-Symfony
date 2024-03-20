<?php
declare(strict_types=1);

namespace App\Components\UserSettings\Persistence;

use App\Entity\BillingAddress;
use Doctrine\ORM\EntityManagerInterface;

class BillingAddressEntityManager
{
    public function __construct
    (
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function persist(BillingAddress $billingAddress): void
    {
        $this->entityManager->persist($billingAddress);
        $this->entityManager->flush();
    }
}