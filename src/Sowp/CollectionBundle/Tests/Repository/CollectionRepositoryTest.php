<?php

namespace Sowp\CollectionBundle\Tests\Repository;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Doctrine\ORM\QueryBuilder;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\CollectionBundle\Entity\CollectionRepository;

class CollectionRepositoryTest extends ApiTestCase
{
    const FIXTURES_COLLECTION_COUNT = 20;

    /**
     * @var CollectionRepository
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();
        $this->container->get('app_bundle.fixtures_loader')->addAll();
        $this->container->get('app_bundle.fixtures_loader')->loadAllFromQueue();
        $this->repository = $this->em->getRepository(Collection::class);
    }

    public function testSearchTitle()
    {
        /**
         * @var $collection Collection|null
         */
        $collection = $this->repository
            ->createQueryBuilder('c')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNotNull($collection);

        $id = $collection->getId();
        $title = 'Verry_complicated_title';
        $collection->setTitle($title);

        $this->em->persist($collection);
        $this->em->flush($collection);

        $result = $this->repository->searchTitle($title);

        $this->assertNotEmpty($result);

        /**
         * @var $result Collection|null
         */
        $result = $result[0];

        $this->assertEquals(
            $result->getId(),
            $id
        );
    }

    public function testGetCollectionsSlug()
    {
        $allCollections = $this->repository->findAll();
        $allSlugs = \array_map(function (Collection $c) {
            return $c->getSlug();
        }, $allCollections);

        $allSlugsFromRepo = $this->repository->getCollectionsSlugs();
        $allSlugsFromRepo = \array_map(function ($a) {
            return $a['slug'];
        }, $allSlugsFromRepo);

        $this->assertNotEmpty($allSlugsFromRepo);
        $this->assertEquals(
            \count($allSlugs),
            \count($allSlugsFromRepo)
        );

        /**
         * Assert that $allSlugsFromRepo contains all slugs from $allSlugs
         */
        foreach ($allSlugs as $slug) {
            $this->assertTrue(\in_array($slug, $allSlugsFromRepo));
        }
    }

    public function testGetQueryBuilderAll()
    {
        $qb = $this->repository->getQueryBuilderAll();

        $this->assertInstanceOf(QueryBuilder::class, $qb);

        $result = $qb->getQuery()->getResult();

        $this->assertEquals(
            \count($result),
            self::FIXTURES_COLLECTION_COUNT
        );
    }

    public function testCountAll()
    {
        $qb = $this->repository->getQueryBuilderAll();
        $colCount = \count($qb->getQuery()->getResult());
        $colCountFromRepo = $this->repository->countAll();

        $this->assertEquals(
            $colCountFromRepo,
            $colCount
        );

        $this->assertEquals(
            $colCountFromRepo,
            self::FIXTURES_COLLECTION_COUNT
        );
    }
}