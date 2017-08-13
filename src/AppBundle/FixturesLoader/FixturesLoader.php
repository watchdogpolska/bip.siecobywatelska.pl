<?php
namespace AppBundle\FixturesLoader;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Sowp\ArticleBundle\DataFixtures\ORM\LoadArticleData;
use Sowp\CollectionBundle\DataFixtures\ORM\DataLoader as CollectionLoader;
use Sowp\NewsModuleBundle\DataFixtures\ORM\DataLoader as NewsLoader;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use AppBundle\DataFixtures\ORM\LoadUserData;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * To load separated objects to database, use *Separate() functions.
 * Internally each loader->execute() also purges database.
 * To load combination of fixtures its necessarry to call
 * apropriate add*() functions AND THEN loadAllFromQueue().
 * It's possible to use handy addAll() and then loadAllFromQueue().
 *
 * Class FixturesLoader
 * @package AppBundle\FixturesLoader
 */
class FixturesLoader
{
    /**
     * @var $em EntityManager
     */
    private $em;

    /**
     * @var $container ContainerInterface
     */
    private $container;

    /**
     * @var $loader ContainerAwareLoader
     */
    private $loader;

    /**
     * @var $purger ORMPurger
     */
    private $purger;

    /**
     * @var $executor ORMExecutor
     */
    private $executor;

    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;

        $this->purger = new ORMPurger();
        $this->executor = new ORMExecutor($this->em, $this->purger);
        $this->loader = new ContainerAwareLoader($this->container);
    }

    public function loadUsersSeparate()
    {
        $this->purgeDatabase();
        $this->executor->execute([new LoadUserData()]);
    }

    public function loadCollectionsSeparate()
    {
        $this->purgeDatabase();
        $this->executor->execute([new CollectionLoader()]);
    }

    public function loadNewsSeparate()
    {
        $this->purgeDatabase();
        $this->executor->execute([new NewsLoader()]);
    }

    public function loadArticlesSeparate()
    {
        $this->purgeDatabase();
        $this->executor->execute([new LoadArticleData()]);
    }

    public function addArticles()
    {
        $this->loader->addFixture(new LoadArticleData());
    }

    public function addUsers()
    {
        $this->loader->addFixture(new LoadUserData());
    }

    public function addNews()
    {
        $this->loader->addFixture(new NewsLoader());
    }

    public function addCollections()
    {
        $this->loader->addFixture(new CollectionLoader());
    }

    public function addAll()
    {
        $this->addUsers();
        $this->addCollections();
        $this->addArticles();
        $this->addNews();
    }

    public function showLoaderQueue()
    {
        return $this->loader->getFixtures();
    }

    public function loadAllFromQueue()
    {
        $this->purgeDatabase();
        $this->executor->execute($this->loader->getFixtures());
    }

    public function purgeDatabase()
    {
        $this->executor->purge();
    }
}