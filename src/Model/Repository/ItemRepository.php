<?php

namespace App\Model\Repository;

use App\Entity\Items;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Items>
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
     */
    public function findOneByItemId(int $itemId): ?Items
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.item_id = :val') // Replace 'itemId' with the actual field name in your Items entity
            ->setParameter('val', $itemId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $teamId
     * @return Items[] Returns an array of Items objects
     */
    public function findManyByTeamId(int $team_id): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.team_id = :val')
            ->setParameter('val', $team_id)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByExternalId($externalId): ?array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.clubName = :val')
            ->setParameter('val', $externalId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find items by an array of item IDs.
     *
     * @param array $itemsWithQuantities An array of arrays, each containing 'id' and 'quantity'
     * @return Items[] Returns an array of Items objects
     */
    public function findItemsByArrayOfIds(array $itemsWithQuantities): array
    {
        // Extract item IDs from the array
        $itemIds = array_column($itemsWithQuantities, 'id');

        // Find items by the extracted IDs
        $query = $this->createQueryBuilder('i')
            ->where('i.item_id IN (:ids)') // Make sure 'id' is the correct field in your Items entity
            ->setParameter('ids', $itemIds)
            ->getQuery();

        // Execute the query and return the result
        return $query->getResult();
    }

}
