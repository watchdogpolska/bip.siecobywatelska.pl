<?php

namespace Sowp\NewsModuleBundle\Tests\Repository;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Doctrine\ORM\QueryBuilder;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\NewsModuleBundle\Entity\NewsRepository;

class NewsRepositoryTest extends ApiTestCase
{
    const FIXTURES_NEWS_COUNT = 100;

    /**
     * @var $repository NewsRepository
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();
        $this->container->get('app_bundle.fixtures_loader')->addAll();
        $this->container->get('app_bundle.fixtures_loader')->loadAllFromQueue();
        $this->repository = $this->em->getRepository(News::class);
    }

    public function testGetQueryBuilderAll()
    {
        $qb = $this->repository->getQueryBuilderAll();

        $this->assertInstanceOf(QueryBuilder::class, $qb);
        $allCount = \count($qb->getQuery()->getResult());
        $this->assertEquals(
            $allCount,
            self::FIXTURES_NEWS_COUNT
        );
    }

    public function testGetTotalNewsCount()
    {
        $allCount = $this->repository->getTotalNewsCount();
        $this->assertEquals(
            $allCount,
            self::FIXTURES_NEWS_COUNT
        );
    }

    public function testGetAndNotDeletedNewsCount() //tests 2 methods
    {
        //we will mock deleting 3 news
        /**
         * @var $newsToDelete News[]|[]
         */
        $newsToDelete = $this->repository
            ->createQueryBuilder('news')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        $dateOfDeletion = new \DateTime('-10 minutes');

        foreach ($newsToDelete as $news) {
            $news->setDeletedAt($dateOfDeletion);
            $this->em->persist($news);
        }

        $this->em->flush();

        // according to fixtures
        $contDeleted = $this->repository->getDeletedNewsCount(); //3 deleted news
        $countNotDeleted = $this->repository->getNotDeletedNewsCount(); //97 not deleted
        $countNotDeletedCalculations = self::FIXTURES_NEWS_COUNT - $contDeleted; //$this should be 97

        $this->assertEquals(
            3,
            $contDeleted
        );

        $this->assertEquals(
            $countNotDeleted,
            $countNotDeletedCalculations
        );
    }
}