<?php

namespace Sowp\NewsModuleBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CollectionRepository extends NestedTreeRepository
{
    public function searchTitle($q)
    {
        $search = "%$q%";
        $query = $this->getEntityManager()
                ->createQuery("SELECT c FROM NewsModuleBundle:Collection c WHERE c.title LIKE :search")
                ->setParameter("search", $search);
        $res = $query->getResult();
        foreach ($res as $k => $v) {
            yield $v;
        }
    }
}
