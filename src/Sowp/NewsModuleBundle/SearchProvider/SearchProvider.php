<?php

namespace Sowp\NewsModuleBundle\SearchProvider;

use Doctrine\ORM\EntityManager;
use Sowp\NewsModuleBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\SearchModuleBundle\Search\SearchResultInterface;

class SearchProvider implements SearchResultInterface
{
    private $em;
    private $qbA;
    private $qbC;
    private $result;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->qbA = $em->getRepository(News::class)->createQueryBuilder('n');
        $this->qbC = $em->getRepository(Collection::class)->createQueryBuilder('c');
    }

    public function search($query)
    {

    }

    public function getResultObject()
    {
        return clone $result;
    }

}