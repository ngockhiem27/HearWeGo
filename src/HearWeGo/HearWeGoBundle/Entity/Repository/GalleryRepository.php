<?php

namespace HearWeGo\HearWeGoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * GalleryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GalleryRepository extends EntityRepository
{
    public function findAll(){
        $query = $this->getEntityManager()->createQuery(
          "SELECT g FROM HearWeGoHearWeGoBundle:Gallery g"
        );
        return $query->getResult();
    }
}
