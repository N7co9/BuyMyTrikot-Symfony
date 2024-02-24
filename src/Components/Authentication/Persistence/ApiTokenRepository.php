<?php

namespace App\Components\Authentication\Persistence;

use App\Entity\ApiToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiToken>
 *
 * @method ApiToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiToken[]    findAll()
 * @method ApiToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiToken::class);
    }

//    /**
//     * @return ApiToken[] Returns an array of ApiToken objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ApiToken
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    /**
     * Returns the user associated with the given token.
     *
     * @param string $token The API token
     * @return \App\Entity\User|null The user associated with the token, or null if not found
     */
    public function findUserByToken(string $token): ?\App\Entity\User
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.ownedBy', 'u')
            ->andWhere('t.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
            ->getOwnedBy(); // Assuming 'ownedBy' is the association property name
    }

    /**
     * Returns the most recent entity saved by the user found by the userId.
     *
     * @param int $userId The user id
     * @return mixed The most recent entity saved by the user
     */
    public function findMostRecentEntityByUserId(int $userId) : ?ApiToken
    {
        return $this->createQueryBuilder('t')
            ->select('t') // Selecting the ApiToken entity
            ->leftJoin('t.ownedBy', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
