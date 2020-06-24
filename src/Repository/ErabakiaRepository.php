<?php

namespace App\Repository;

use App\Entity\Erabakia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Erabakia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Erabakia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Erabakia[]    findAll()
 * @method Erabakia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ErabakiaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Erabakia::class);
    }

    public function getAllQuery($filter, $extra): Query
    {
        $qb = $this->createQueryBuilder( 'l' );

        if ( $filter ) {
            if ( $extra['testua'] !== null ) {
                $qb->orWhere( 'l.gaiak LIKE :testua' )->setParameter( 'testua', '%' . $extra[ 'testua' ] . '%' );
                $qb->orWhere( 'l.temas LIKE :testua' )->setParameter( 'testua', '%' . $extra[ 'testua' ] . '%' );
                $qb->orWhere( 'l.oharrak LIKE :testua' )->setParameter( 'testua', '%' . $extra[ 'testua' ] . '%' );
                $qb->orWhere( 'l.observaciones LIKE :testua' )->setParameter( 'testua', '%' . $extra[ 'testua' ] . '%' );
            }
            if ($extra['datatik'] !== null) {
                $qb->andWhere( 'l.adata >= :hasiera' )->setParameter( 'hasiera', $extra['datatik'] );
            }
            if ($extra['datara'] !== null) {
                $qb->andWhere( 'l.adata <= :amaiera' )->setParameter( 'amaiera', $extra['datara'] );
            }
            if ($filter->getLiburua()) {
                $qb->leftJoin( 'l.liburua', 'li' )
                   ->andWhere( 'li.id = :liburuaid' )->setParameter('liburuaid', $filter->getLiburua()->getId());
            }
        }

        $qb->orderBy( 'l.adata', 'ASC' );

        return $qb->getQuery();
    }
}
