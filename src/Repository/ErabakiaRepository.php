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
            if ($extra['testua'] === 'Bilaketa testua / Texo a buscar') {
                $extra['testua'] = null;
            }
            if ( $extra['testua'] !== null ) {
                $qb->andWhere('MATCH_AGAINST(l.gaiak, l.temas,l.oharrak, l.observaciones) AGAINST (:searchterm boolean) > 0')->setParameter('searchterm',$extra[ 'testua' ]);
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
        $berrogehitahamar = new \DateTime(date('Y-m-d'));
        $berrogehitahamar->modify('-50 year');
        $kendu = $this->createQueryBuilder('ee')->andWhere('ee.adata >= :adata')->andWhere('ee.akta=0');
        $qb->andWhere($qb->expr()->notIn('l.id', $kendu->getDQL()))->setParameter('adata', $berrogehitahamar);
        $qb->orderBy( 'l.adata', 'DESC' );

        return $qb->getQuery();
    }

}
