<?php

namespace Sowp\ArticleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\CollectionBundle\Entity\Collection;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    private $faker;
    private $manager;

    public function __construct()
    {
        $this->faker = $faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadArticles($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadArticles(ObjectManager $manager)
    {

        $all_collections = $manager->getRepository(Collection::class)->findAll();

        for ($i = 0; $i < 100; ++$i) {
            $article = new Article();
            $article->setTitle($this->faker->text(255));
            $article->setContent($this->faker->paragraph(20));
            $article->setCreatedAt($this->faker->dateTimeBetween('-5 years', 'now'));
            $article->setEditNote($this->faker->text());
            $article_collections = $this->faker->randomElements($all_collections, $this->faker->numberBetween(0, 4));
            foreach ($article_collections as $collection) {
                $article->addCollection($collection);
            }
            $manager->persist($article);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
