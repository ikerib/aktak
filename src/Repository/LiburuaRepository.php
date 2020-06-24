<?php

namespace App\Repository;

use App\Entity\Liburua;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\VarDumper\Dumper\esc;

/**
 * @method Liburua|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liburua|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liburua[]    findAll()
 * @method Liburua[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiburuaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Liburua::class);
    }

    public function getAllQuery($filter, $extra): Query
    {
        $qb = $this->createQueryBuilder( 'l' );

        if ( $filter ) {
            if ($filter->getKodea()) {
                $qb->andWhere( 'l.kodea LIKE :kodea')->setParameter('kodea', '%' .  $filter->getKodea() . '%' );
            }
            if ($extra['hasieratik'] !== null) {
                $qb->andWhere( 'l.hasiera >= :hasiera' )->setParameter( 'hasiera', $extra['hasieratik'] );
            }
            if ($extra['hasierara'] !== null) {
                $qb->andWhere( 'l.hasiera <= :hasiera' )->setParameter( 'hasiera', $extra['hasierara'] );
            }
            if ($extra['amaieratik'] !== null) {
                $qb->andWhere( 'l.amaiera >= :amaiera' )->setParameter( 'amaiera', $extra['amaieratik'] );
            }
            if ($extra['amaierara'] !== null) {
                $qb->andWhere( 'l.amaiera <= :amaiera' )->setParameter( 'amaiera', $extra['amaierara'] );
            }
            if ($filter->getEgoera()) {
                $qb->leftJoin( 'l.egoera', 'e' )
                   ->andWhere( 'e.id = :egoieraid' )->setParameter('egoieraid', $filter->getEgoera()->getId());
            }
            if ($filter->getMota()) {
                $qb->leftJoin( 'l.mota', 'm' )
                   ->andWhere( 'm.id = :motaid' )->setParameter('motaid', $filter->getMota()->getId());
            }
        }

        $qb->orderBy( 'l.id', 'DESC' );

        return $qb->getQuery();
    }
}
