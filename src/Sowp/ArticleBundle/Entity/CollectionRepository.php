<?php

namespace Sowp\ArticleBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CollectionRepository.
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class CollectionRepository extends EntityRepository
{
    public function search($q){
        return $this->createQueryBuilder('node')
            ->where('node.name LIKE :name')
            ->setParameter('name', '%' . $q . '%')->getQuery()->getResult();
    }
}
