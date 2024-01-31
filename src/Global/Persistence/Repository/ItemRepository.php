<?php

namespace App\Global\Persistence\Repository;

use App\Entity\Items;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @extends ServiceEntityRepository
 * +<Items>
 *
 * @method Items|null find($id, $lockMode = null, $lockVersion = null)
 * @method Items|null findOneBy(array $criteria, array $orderBy = null)
 * @method Items[]    findAll()
 * @method Items[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Items::class);
    }


    /**
     * Find an item by its item ID.
     *
     * @param int $itemId
     * @return Items|null Returns an Items object or null
     * @throws NonUniqueResultException
     */
    public function findOneByItemId(int $itemId): ?Items
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.item_id = :val')
            ->setParameter('val', $itemId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByExternalId($externalId): ?array
    {
        {
            return $this->createQueryBuilder('i')
                ->andWhere('i.clubName = :val')
                ->setParameter('val', $externalId)
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * Find items by an array of item IDs.
     *
     * @param array $itemsWithQuantities An array of arrays, each containing 'id' and 'quantity'
     * @return Items[] Returns an array of Items objects
     */
    public function findItemsByArrayOfIds(array $itemsWithQuantities): array
    {
        $itemIds = array_column($itemsWithQuantities, 'id');

        $query = $this->createQueryBuilder('i')
            ->where('i.item_id IN (:ids)')
            ->setParameter('ids', $itemIds)
            ->getQuery();

        return $query->getResult();
    }
}
