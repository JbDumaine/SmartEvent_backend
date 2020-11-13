<?php

namespace App\Repository;

use App\Entity\EventsItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventsItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventsItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventsItems[]    findAll()
 * @method EventsItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventsItems::class);
    }

    // /**
    //  * @return EventsItems[] Returns an array of EventsItems objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventsItems
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
