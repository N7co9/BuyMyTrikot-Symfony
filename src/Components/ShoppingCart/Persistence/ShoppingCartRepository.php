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
        return $this->createQueryBuilder('s')
            ->andWhere('s.user_id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function getTotal(int $userId) : array // return subtotal, tax, shipping & total
    {
        $itemsInCart = $this->findByUserId($userId);
        $subTotal = 0.00;
        foreach ($itemsInCart as $item)
        {
            $subTotal += $item->getPrice() * $item->getQuantity();
        }
        $tax = $subTotal * 0.19;
        $shipping = 4.95;
        $total = $subTotal + $shipping + $tax;

        return [
            'tax' => $tax,
            'shipping' => $shipping,
            'subTotal' => $subTotal,
            'total' => $total
            ];
    }
    /**
     * Get all item ids and quantities for items in the shopping cart of a user identified by email.
     *
     * @param string $email User's email
     * @return array Returns an array of arrays with item_id and quantity
     */
    public function findCartItemsByEmail(string $email): array
    {
        $query = $this->createQueryBuilder('sc')
            ->select('sc.item_id AS id', 'sc.quantity AS quantity')
            ->innerJoin('App\Entity\User', 'u', 'WITH', 'sc.user_id = u.id')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery();

        return $query->getArrayResult();
    }


//    /**
//     * @return Item[] Returns an array of Item objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
