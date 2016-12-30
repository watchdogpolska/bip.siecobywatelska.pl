<?php

namespace Sowp\ArticleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\ArticleBundle\Entity\Collection;

class LoadArticleData implements FixtureInterface
{
    private $faker;
    private $manager;

    private $collections = [];

    public function __construct()
    {
        $this->faker = $faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadCollections($manager);
        $this->loadArticles($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     *
     * @return int
     */
    public function loadCollections()
    {
        for ($i = 0; $i < 100; ++$i) {
            $collection = new Collection();
            $collection->setName($this->faker->word);
            $this->manager->persist($collection);
            $this->collections[] = $collection;
        }
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadArticles(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll() + [];

        for ($i = 0; $i < 100; ++$i) {
            $article = new Article();
            $article->setTitle($this->faker->text(255));
            $article->setContent($this->faker->paragraph(20));
            $article->setCreatedBy($this->faker->randomElement($users));
            $article->setModifitedBy($this->faker->randomElement($users));
            $article->setCreatedAt($this->faker->dateTimeBetween('-5 years', 'now'));
            $article->setEditNote($this->faker->text());
            $article_collection = $this->faker->randomElements($this->collections, $this->faker->numberBetween(0, 4));
            foreach ($article_collection as $c) {
                $article->addCollection($c);
            }
            $manager->persist($article);
        }
    }
}
