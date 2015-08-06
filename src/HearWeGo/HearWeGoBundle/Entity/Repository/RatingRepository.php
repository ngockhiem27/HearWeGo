<?php

namespace HearWeGo\HearWeGoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RatingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RatingRepository extends EntityRepository
{
    public function findByAudioAndUser($user_id,$audio_id)
    {
        return $this->getEntityManager()->createQuery('SELECT r FROM HearWeGoHearWeGoBundle:Rating r WHERE (IDENTITY(r.user)=:user_id AND IDENTITY(r.audio)=:audio_id)')->setParameters(array('user_id'=>$user_id,'audio_id'=>$audio_id))->getOneOrNullResult();
    }
}
