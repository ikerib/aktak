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

    public function base($filter, $extra): \Doctrine\ORM\QueryBuilder
    {
        $qb = $this->createQueryBuilder( 'l' );
        $qb->select('l,li');
        $qb->leftJoin('l.liburua', 'li');

        if ( $filter ) {
            if ( $extra['testua'] !== null ) {
//                $qb->orWhere( 'l.gaiak LIKE :testua' )->setParameter( 'testua', '%' . $extra[ 'testua' ] . '%' );
//                $qb->orWhere( 'l.temas LIKE :testua' )->setParameter( 'testua', '%' . $extra[ 'testua' ] . '%' );
//                $qb->orWhere( 'l.oharrak LIKE :testua' )->setParameter( 'testua', '%' . $extra[ 'testua' ] . '%' );
//                $qb->orWhere( 'l.observaciones LIKE :testua' )->setParameter( 'testua', '%' . $extra[ 'testua' ] . '%' );
                $qb->orWhere('MATCH_AGAINST(l.gaiak, l.temas,l.oharrak, l.observaciones) AGAINST (:searchterm boolean) > 0')->setParameter('searchterm',$extra[ 'testua' ]);
            }
            if ($extra['datatik'] !== null) {
                $qb->andWhere( 'l.adata >= :hasiera' )->setParameter( 'hasiera', $extra['datatik'] );
            }
            if ($extra['datara'] !== null) {
                $qb->andWhere( 'l.adata <= :amaiera' )->setParameter( 'amaiera', $extra['datara'] );
            }
            if ($filter->getLiburua()) {
                $qb->andWhere( 'li.id = :liburuaid' )->setParameter('liburuaid', $filter->getLiburua()->getId());
            }
        }
        return $qb;
    }

    public function getAllQuery($filter, $extra): Query
    {
        $qb = $this->base($filter, $extra);
        $qb->orderBy( 'l.id', 'DESC' );

        return $qb->getQuery();
    }



    public function getAllInternet($filter, $extra): Query
    {
        $qb = $this->base($filter, $extra);

        // Publikoan akta=0 eta orain 50urtekoak soilik erakutsi behar ditu
//        $qb->andWhere('l.akta=1 OR l.akta IS NULL');
        $qb->andWhere('l.akta=1');
        $berrogehitahamar = new \DateTime(date('Y-m-d'));
        $berrogehitahamar->modify('-50 year');
        $qb->andWhere('l.adata <= :berrogehitahamar')->setParameter('berrogehitahamar', $berrogehitahamar);

        $qb->orderBy( 'l.adata', 'ASC' );

        return $qb->getQuery();
    }

}
