<?php

namespace App\Components\Orderflow\Persistence;

use App\Entity\BillingAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BillingAddress>
 *
 * @method BillingAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillingAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillingAddress[]    findAll()
 * @method BillingAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillingAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillingAddress::class);
    }

    /**
     * Find a BillingAddress entity by user ID.
     *
     * @param int $userId
     * @return BillingAddress|null
     */
    public function findByUserId(int $userId): ?BillingAddress
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
