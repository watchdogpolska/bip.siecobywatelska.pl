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

    public function getTotalNewsCount()
    {

    }

    public function getDeletedNewsCount()
    {

    }

    public function getNotDeletedNewsCount()
    {

    }

    public function getNewsByCategory(\Sowp\NewsModuleBundle\Entity\Collection $collection)
    {

    }

    public function getNewsCountByCategory(\Sowp\NewsModuleBundle\Entity\Collection $collection)
    {

    }
}
