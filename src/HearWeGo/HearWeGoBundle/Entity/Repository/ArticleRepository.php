<?php

namespace HearWeGo\HearWeGoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function findAll(){
        $query = $this->getEntityManager()->createQuery(
          "SELECT a FROM HearWeGoHearWeGoBundle:Article a"
        );
        return $query->getResult();
    }
}
