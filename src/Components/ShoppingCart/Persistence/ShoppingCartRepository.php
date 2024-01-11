<?php

namespace App\Components\ShoppingCart\Persistence;

use App\Entity\ShoppingCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class ShoppingCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingCart::class);
    }

    /**
     * @param $userId
     * @param $itemId
     * @return ShoppingCart|null Returns a ShoppingCart object or null
     * @throws NonUniqueResultException
     */
    public function findOneByUserIdAndItemId($userId, $itemId): ?ShoppingCart
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user_id = :userId')
            ->andWhere('s.item_id = :itemId')
            ->setParameter('userId', $userId)
            ->setParameter('itemId', $itemId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */

    /**
     * @param int $userId
     * @return ShoppingCart[] Returns an array of ShoppingCart objects
     */
    public function findByUserId(int $userId): array
    {
        return $this->findBy(['user_id' => $userId]);
    }

    /**
     * @param int $userId
     * @return array Returns an array of arrays with item_id and quantity
     */
    public function findCartItemsByUserId(int $userId): array
    {
        $query = $this->createQueryBuilder('sc')
            ->select('sc.item_id AS id', 'sc.quantity AS quantity')
            ->where('sc.user_id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();

        return $query->getArrayResult();
    }
}
