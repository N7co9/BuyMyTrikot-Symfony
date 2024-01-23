<?php

namespace App\Components\ShoppingCart\Persistence;

use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\Mapping\ShoppingCartMapping as ShoppingCartMapper;
use App\Entity\ShoppingCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class ShoppingCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly ShoppingCartMapper $shoppingCartMapper)
    {
        parent::__construct($registry, ShoppingCart::class);
    }


    public function findOneByUserIdAndItemId(int $userId, int $itemId): ?ShoppingCartSaveDTO
    {
        $shoppingCart = $this->findOneBy(['user_id' => $userId, 'item_id' => $itemId]);

        if ($shoppingCart instanceof ShoppingCart) {
            return $this->shoppingCartMapper->mapEntityToDto($shoppingCart);
        }

        return null;
    }

    /**
     * @throws NonUniqueResultException
     */

    /**
     * @param int $userId
     * @return ShoppingCartSaveDTO[] Returns an array of ShoppingCart objects
     */
    public function findByUserId(int $userId): array
    {
        $shoppingCartSaveDtoList = [];
        $shoppingCart = $this->findBy(['user_id' => $userId]);

        foreach ($shoppingCart as $item)
        {
            if ($item instanceof ShoppingCart)
            {
                $shoppingCartSaveDtoList [] = $this->shoppingCartMapper->mapEntityToDto($item);
            }
        }
        return  $shoppingCartSaveDtoList;
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
