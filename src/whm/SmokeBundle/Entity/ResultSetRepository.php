<?php

namespace whm\SmokeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ResultSetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResultSetRepository extends EntityRepository
{
    public function findNewest($count)
    {
        $qb = $this->createQueryBuilder('rs');
        $qb->groupBy("rs.url");
        $qb->orderBy('rs.id', 'DESC');
        $qb->setMaxResults($count);
        return $qb->getQuery()->getResult();
    }
}
