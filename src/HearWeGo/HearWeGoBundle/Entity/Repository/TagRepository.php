<?php

namespace HearWeGo\HearWeGoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class TagRepository extends EntityRepository
{

    public function searchTags($value)
    {
        $tagNames = array_filter(explode(",", $value));
        array_walk($tagNames, function ($item) {
            return trim($item);
        });
        $qb = $this->createQueryBuilder("tag");
        $qb->where($qb->expr()->in("tag.name", $tagNames));
        $tags = $qb->getQuery()->getResult();

        return $tags;
    }
}