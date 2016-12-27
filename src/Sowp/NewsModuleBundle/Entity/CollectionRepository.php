<?php

namespace Sowp\NewsModuleBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CollectionRepository extends NestedTreeRepository
{
    public function searchTitle($q)
    {
        $search = "%$q%";
        $query = $this->getEntityManager()
                ->createQuery('SELECT c FROM NewsModuleBundle:Collection c WHERE c.title LIKE :search')
                ->setParameter('search', $search);
        $res = $query->getResult();

        return $res;
    }

    public function getCollectionsIds()
    {
        return $this->getEntityManager()
            ->createQueryBuilder('c')
            ->select('c.id')
            ->from('NewsModuleBundle:Collection', 'c')
            ->getQuery()
            ->getArrayResult();
    }

    public function getCollectionsSlugs()
    {
        return $this->getEntityManager()
            ->createQueryBuilder('c')
            ->select('c.slug')
            ->from('NewsModuleBundle:Collection', 'c')
            ->getQuery()
            ->getArrayResult();
    }
}
