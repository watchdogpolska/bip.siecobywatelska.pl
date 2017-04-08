<?php

namespace Sowp\NewsModuleBundle\Entity;

use Doctrine\ORM\EntityRepository;

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
        return $this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->andWhere('n.deletedAt IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getNotDeletedNewsCount()
    {
        return $this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->andWhere('n.deletedAt IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getNewsCountByCategory(\Sowp\NewsModuleBundle\Entity\Collection $collection)
    {
//        $conn = $this->getEntityManager()->getConnection();
//        $stmt = $conn->prepare('SELECT COUNT(*) AS count FROM collection_news WHERE collection_id = ? ');
//        $stmt->bindValue(1, $collection->getId());
//        $stmt->execute();
//
//        return $stmt->fetch(\PDO::FETCH_OBJ);
        return 0;
    }
}
