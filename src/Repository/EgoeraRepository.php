<?php

namespace App\Repository;

use App\Entity\Egoera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Egoera|null find($id, $lockMode = null, $lockVersion = null)
 * @method Egoera|null findOneBy(array $criteria, array $orderBy = null)
 * @method Egoera[]    findAll()
 * @method Egoera[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EgoeraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Egoera::class);
    }

    // /**
    //  * @return Egoera[] Returns an array of Egoera objects
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
    public function findOneBySomeField($value): ?Egoera
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
