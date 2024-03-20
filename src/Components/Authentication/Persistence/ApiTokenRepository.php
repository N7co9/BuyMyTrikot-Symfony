<?php

namespace App\Components\Authentication\Persistence;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    /**
     * Returns the user associated with the given token.
     *
     * @param string $token The API token
     * @return User|null The user associated with the token, or null if not found
     * @throws NonUniqueResultException
     */
    public function findUserByToken(string $token): ?User
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.ownedBy', 'u')
            ->andWhere('t.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
            ?->getOwnedBy();
    }

    /**
     * Returns the most recent entity saved by the user found by the userId.
     *
     * @param int $userId The user id
     * @return ApiToken|null The most recent entity saved by the user
     * @throws NonUniqueResultException
     */
    public function findMostRecentEntityByUserId(int $userId): ?ApiToken
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->leftJoin('t.ownedBy', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
