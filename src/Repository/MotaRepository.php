<?php

namespace App\Repository;

use App\Entity\Mota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mota[]    findAll()
 * @method Mota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mota::class);
    }

    // /**
    //  * @return Mota[] Returns an array of Mota objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mota
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
