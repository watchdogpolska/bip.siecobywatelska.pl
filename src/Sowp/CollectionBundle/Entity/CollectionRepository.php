<?php

namespace Sowp\CollectionBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CollectionRepository extends NestedTreeRepository
{
    public function searchTitle($q)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM CollectionBundle:Collection c WHERE c.title LIKE :search')
            ->setParameter('search', "%$q%")
            ->getResult();
    }

    public function getCollectionsSlugs()
    {
        return $this->getEntityManager()
            ->createQueryBuilder('c')
            ->select('c.slug')
            ->from('CollectionBundle:Collection', 'c')
            ->getQuery()
            ->getArrayResult();
    }

    public function getQueryBuilderAll()
    {
        return $this
            ->createQueryBuilder('collection');
    }

    public function countAll()
    {
        return $this
            ->createQueryBuilder('col')
            ->select('COUNT(col.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}