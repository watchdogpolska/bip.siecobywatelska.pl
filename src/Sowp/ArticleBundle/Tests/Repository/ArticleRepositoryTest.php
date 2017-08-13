<?php
namespace Sowp\ArticleBundle\Tests\Repository;

use AppBundle\Entity\User;
use AppBundle\Tests\ApiUtils\ApiTestCase;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\ArticleBundle\Entity\ArticleRepository;

class ArticleRepositoryTest extends ApiTestCase
{
    const FIXTURES_ARTICLE_COUNT = 100;

    /**
     * @var $repository ArticleRepository
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();
        $this->container->get('app_bundle.fixtures_loader')->addAll();
        $this->container->get('app_bundle.fixtures_loader')->loadAllFromQueue();
        $this->repository = $this->em->getRepository(Article::class);
    }

    /**
     * @param $count
     * @return int[]|[]
     */
    private function setArticlesDeleted($count)
    {
        $ids = [];

        // we know from fixtures that ther is no deleted article currently
        // so we will set $count articles to be deleted
        $articles = $this->repository
            ->createQueryBuilder('article')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();

        foreach ($articles as $article) {
            $ids[] = $article->getId();
            $minutes = mt_rand(12, 45);
            $date = new \DateTime();

            $date->modify("-$minutes minutes");
            $article->setDeletedAt($date);
            $this->em->persist($article);
        }

        $this->em->flush();

        return $ids;
    }

    public function testFindAllQueryBuilder()
    {
        $qb = $this->repository->findAllQueryBuilder();

        $this->assertInstanceOf(QueryBuilder::class, $qb, "testFindAllQueryBuilder class instance");

        $result = $qb->getQuery()->getResult();

        // We assert 100 articles - fixtures load this and we purge database
        $this->assertEquals(self::FIXTURES_ARTICLE_COUNT, count($result), "testFindAllQueryBuilder count");
    }

    public function testFindAllQuery()
    {
        $query = $this->repository->findAllQuery();
        $this->assertInstanceOf(Query::class, $query);
        $result = $query->getResult();
        $this->assertEquals(self::FIXTURES_ARTICLE_COUNT, \count($result), "testFindAllQuery count");
    }

    public function testFindDeletedQueryBuilder()
    {
        $this->setArticlesDeleted(2);
        $result = $this->repository->findDeletedQueryBuilder()->getQuery()->getResult();
        $this->assertCount(2, $result);
    }

    public function testFindDeletedQuery()
    {
        $this->setArticlesDeleted(2);
        $result = $this->repository->findDeletedQuery()->getResult();
        $this->assertCount(2, $result);
    }

    public function testFindDeleted()
    {
        $this->setArticlesDeleted(2);
        $result = $this->repository->findDeleted();
        $this->assertCount(2, $result);
    }

    /**
     * This test also covers ArticleRepository::filterById()
     * since ArticleRepository::findDeletedById() uses it internally
     */
    public function testFindDeletedBiId_filterById()
    {
        $ids = $this->setArticlesDeleted(2);
        $result = $this->repository->findDeletedById($ids[0]);

        $this->assertNotNull($result);
        $this->assertEquals($ids[0], $result->getId());
    }

    public function testFindPublished()
    {
        $result = $this->repository->findPublished();
        $this->assertCount(self::FIXTURES_ARTICLE_COUNT, $result);
    }

    public function testFindPublishedById()
    {
        $articles = $this->repository
            ->createQueryBuilder('a')
            ->select('a.id')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        $article = $articles[0]['id'];
        $result = $this->repository->findPublishedById($article);

        $this->assertNotNull($result);
        $this->assertEquals($article, $result->getId());
    }

    /**
     * this tests also findAllWithAutorsQueryBuilder
     */
    public function testFindPublishedWithAuthorsQueryBuilder()
    {
        /**
         * @var $user User|null
         */
        $user = $this->em
            ->getRepository(User::class)
            ->createQueryBuilder('u')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNotNull($user);
        $articles = $this->repository->findAll();

        /**
         * @var $article Article|null
         */
        $article = $articles[0];
        $title = $this->faker->text(20,50);

        $article->setTitle($title);
        $article->setCreatedBy($user);
        $this->em->persist($article);
        $this->em->flush($article);

        /**
         * @var $result Article|null
         */
        $result = $this->repository
            ->findPublishedWithAuthorsQueryBuilder()
            ->getQuery()
            ->getResult();

        $this->assertNotEmpty($result);
        $result = $result[0];

        $this->assertEquals(
            $title,
            $result->getTitle()
        );
        $this->assertEquals(
            $result->getCreatedBy()->getId(),
            $user->getId()
        );
    }

    public function testCountAllArticles()
    {
        $count = $this->repository->countAllArticles();
        $realCount = \count($this->repository->findAll());
        $this->assertEquals(
            $count,
            $realCount
        );
    }
}