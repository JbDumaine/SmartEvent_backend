<?php

namespace App\Repository;

use App\Entity\AuthAccessTokens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuthAccessTokens|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthAccessTokens|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthAccessTokens[]    findAll()
 * @method AuthAccessTokens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthAccessTokensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthAccessTokens::class);
    }

    // /**
    //  * @return AuthAccessTokens[] Returns an array of AuthAccessTokens objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AuthAccessTokens
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
