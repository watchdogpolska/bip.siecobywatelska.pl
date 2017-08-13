<?php

namespace Sowp\NewsModuleBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Sowp\CollectionBundle\Entity\Collection;

class NewsRepository extends EntityRepository
{
    public function getQueryBuilderAll()
    {
        return $this
            ->createQueryBuilder('n')
            ->addOrderBy('n.pinned', 'DESC')
            ->addOrderBy('n.createdAt', 'DESC');
    }

    public function getTotalNewsCount()
    {
        return $this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getDeletedNewsCount()
    {
        $c = $this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->andWhere('n.deletedAt IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();

        return (int)$c;
    }

    public function getNotDeletedNewsCount()
    {
        $c = $this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->andWhere('n.deletedAt IS NULL')
            ->getQuery()
            ->getSingleScalarResult();

        return (int)$c;
    }

}
