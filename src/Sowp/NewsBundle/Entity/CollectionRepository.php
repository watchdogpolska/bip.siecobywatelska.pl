<?php

namespace Sowp\NewsBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CollectionRepository extends NestedTreeRepository
{
    public function searchTitle($q)
    {
        $search = "%$q%";
        $query = $this->getEntityManager()
                ->createQuery('SELECT c FROM NewsBundle:Collection c WHERE c.title LIKE :search')
                ->setParameter('search', $search);
        $res = $query->getResult();

        return $res;
    }

    public function getCollectionsSlugs()
    {
        return $this->getEntityManager()
            ->createQueryBuilder('c')
            ->select('c.slug')
            ->from('NewsBundle:Collection', 'c')
            ->getQuery()
            ->getArrayResult();
    }
}
