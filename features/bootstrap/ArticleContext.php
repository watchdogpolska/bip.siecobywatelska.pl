<?php

use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\ArticleBundle\Entity\Collection;

class ArticleContext implements Context
{
    use Behat\Symfony2Extension\Context\KernelDictionary;
    use DoctrineDictrionary;

    /** @var Behat\MinkExtension\Context\MinkContext */
    private $minkContext;
    /** @var UserContext */
    private $userContext;
    /** @var \Faker\Generator */
    private $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('pl_PL');
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        /** @var Behat\Behat\Context\Environment\InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
        $this->userContext = $environment->getContext('UserContext');
    }

    /**
     * @Given :num collections should exist
     */
    public function numCollectionsShouldExists($num)
    {
        for ($i = 0; $i < $num; ++$i) {
            $name = $this->faker->streetName;
            $this->createCollection($name);
        }
    }

    /**
     * @Given /^The following collections exist$/
     */
    public function theFollowingCollectionsExist(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $name = $row['name'];
            $this->createCollection($name);
        }
    }

    /**
     * @Given /^The collection "([^"]*)" exists$/
     */
    public function theCollectionExists($name)
    {
        $this->createCollection($name);
    }

    public function createCollection($name)
    {
        $em = $this->getManager();

        $collection = new Collection();
        $collection->setName($name);
        $em->persist($collection);
        $em->flush();

        return $collection;
    }

    /**
     * @Given /^(\d+) articles should exist$/
     */
    public function numArticlesShouldExists($num)
    {
        $this->numCollectionsShouldExists(($num / 5) | 0);
        $this->userContext->numUsersShouldExists(($num / 5) | 0);
        for ($i = 0; $i < $num; ++$i) {
            $this->createArticle();
        }
    }

    /**
     * @Given /^he following articles exist$/
     */
    public function theFollowingArticleExist(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->createArticle();
        }
    }

    /**
     * @Given /^The article "([^"]*)" exists$/
     */
    public function theArticleExists($title)
    {
        $this->createArticle(['title' => $title]);
    }

    public function createArticle($opts = [])
    {
        $userRepo = $this->getManager()->getRepository(User::class);
        $users = $userRepo->findAll();
        $collections = $this->getManager()->getRepository(Collection::class)->findAll();


        $em = $this->getManager();

        $article = new Article();
        if (!isset($opts['title'])) {
            $opts['title'] = $this->faker->words(3, true);
        }
        if (!isset($opts['content'])) {
            $opts['content'] = $this->faker->paragraphs(20, true);
        }
        if (!isset($opts['created_at'])) {
            $opts['created_at'] = $this->faker->dateTimeBetween('-5years');
        }
        if (!isset($opts['created_by'])) {
            $opts['created_by'] = $this->faker->randomElement($users);
        }
        if (!isset($opts['modifited_at']) && !isset($opts['modifited_by']) && $this->faker->boolean) {
            $opts['modifited_at'] = null;
            $opts['modifited_by'] = null;
        }
        if (!isset($opts['modifited_at'])) {
            $opts['modifited_at'] = $this->faker->dateTimeBetween($opts['created_at']);
        }
        if (!isset($opts['modifited_by'])) {
            $opts['modifited_by'] = $this->faker->randomElement($users);
        }
        if (!isset($opts['collections'])) {
            $count = $this->faker->numberBetween(1, 10);
            $opts['collections'] = $this->faker->randomElements($collections, $count);
        }
        if (!isset($opts['edit_note'])) {
            $opts['edit_note'] = $this->faker->text(200);
        }
        $article->setTitle($opts['title']);
        $article->setContent($opts['content']);
        $article->setCreatedAt($opts['created_at']);
        $article->setCreatedBy($opts['created_by']);
        $article->setModifitedAt($opts['modifited_at']);
        $article->setModifitedBy($opts['modifited_by']);
        foreach ($opts['collection'] as $collection) {
            $article->addCollection($opts['collection']);
        }
        $article->setEditNote($opts['edit_note']);
        $em->persist($article);
        $em->flush();

        return $article;
    }
}
