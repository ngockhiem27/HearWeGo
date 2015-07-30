<?php

namespace HearWeGo\HearWeGoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TourRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TourRepository extends EntityRepository
{
    public function findNewTour( $num ){
        $date = new \DateTime();
        $query = $this->getEntityManager()->createQuery(
            'SELECT t FROM HearWeGoHearWeGoBundle:Tour t WHERE t.startdate > :date ORDER BY t.createdAt DESC'
        )->setParameter('date', $date)->setMaxResults( $num );
        return $query->getResult();
    }

    public function findSaleTour( $num ){
        $date = new \DateTime();
        $query = $this->getEntityManager()->createQuery(
            'SELECT t FROM HearWeGoHearWeGoBundle:Tour t WHERE t.startdate > :date ORDER BY t.discount DESC'
        )->setParameter('date', $date)->setMaxResults( $num );
        return $query->getResult();
    }

}
