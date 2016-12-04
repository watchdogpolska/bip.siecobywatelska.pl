<?php

namespace Sowp\NewsModuleBundle\Entity;

use \Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{
    public function getQueryBuilderAll()
    {
        return $this
            ->createQueryBuilder('n')
            ->addOrderBy('n.pinned', 'DESC')
            ->addOrderBy('n.createdAt', 'DESC');
    }
}
