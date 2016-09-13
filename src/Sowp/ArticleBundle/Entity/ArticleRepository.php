<?php

namespace Sowp\ArticleBundle\Entity;

use Doctrine\ORM\EntityRepository;


/**
 * ArticleRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function findDeletedQueryBuilder(){
        $em = $this->getEntityManager();
        $em->getFilters()->disable('softdeleteable');
        $now = new \DateTime('now');

        $qb = $em->createQueryBuilder();
        $qb = $qb->select('a')
            ->from(Article::class, 'a')
            ->where('a.deletedAt < ?1')
            ->setParameter(1, $now);
        $em->getFilters()->enable('softdeleteable');
        return $qb;
    }

    public function findDeletedQuery(){
        return $this->findDeletedQueryBuilder()->getQuery();
    }

    public function findDeleted(){
        return $this->findDeletedQuery()->getResult();
    }

}