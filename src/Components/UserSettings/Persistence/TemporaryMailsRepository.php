<?php

namespace App\Components\UserSettings\Persistence;

use App\Entity\TemporaryMails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TemporaryMails>
 *
 * @method TemporaryMails|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemporaryMails|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemporaryMails[]    findAll()
 * @method TemporaryMails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemporaryMailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemporaryMails::class);
    }

    /**
     * Get the email value for a specific owned_by integer value.
     *
     * @param int $ownedBy
     * @return string|null
     */
    public function findEmailByOwnedBy(int $ownedBy): ?string
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.email')
            ->andWhere('t.owned_by = :owned_by')
            ->setParameter('owned_by', $ownedBy)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result['email'] : null;
    }

    /**
     * Get the TemporaryMails entity for a specific owned_by integer value.
     *
     * @param int $ownedBy
     * @return TemporaryMails|null
     */
    public function findOneByOwnedBy(int $ownedBy): ?TemporaryMails
    {
        return $this->findOneBy(['owned_by' => $ownedBy]);
    }

}
