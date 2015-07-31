<?php

namespace HearWeGo\HearWeGoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AudioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AudioRepository extends EntityRepository
{
    public function findAll(){
        $query = $this->getEntityManager()->createQuery(
            "SELECT a FROM HearWeGoHearWeGoBundle:Audio a"
        );
        return $query->getResult();
    }
    public function findById($id)
    {
        return $this->getEntityManager()->createQuery('SELECT a FROM HearWeGoHearWeGoBundle:Audio a WHERE a.id=:id')->setParameter('id',$id)->getOneOrNullResult();
    }
    public function findHotAudio( $num ){
        $query = $this->getEntityManager()->createQuery(
          "SELECT a FROM HearWeGoHearWeGoBundle:Audio a ORDER BY a.sales ASC"
        )->setMaxResults( $num );
        return $query->getResult();
    }
}
