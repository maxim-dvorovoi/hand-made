<?php

namespace AppBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function get9Products()
    {
        return $this
            ->createQueryBuilder('p')
            ->orderBy('p.views','DESC')
            ->where('p.active = 1')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
            ;
    }

    public function get3Products()
    {
        return $this
            ->createQueryBuilder('p')
            ->orderBy('p.id','DESC')
            ->where('p.active = 1')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }
}