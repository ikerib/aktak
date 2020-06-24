<?php

namespace App\Repository;

use App\Entity\Izena;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Izena|null find($id, $lockMode = null, $lockVersion = null)
 * @method Izena|null findOneBy(array $criteria, array $orderBy = null)
 * @method Izena[]    findAll()
 * @method Izena[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IzenaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Izena::class);
    }

    // /**
    //  * @return Izena[] Returns an array of Izena objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Izena
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
