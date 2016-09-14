<?php

namespace Sowp\ArticleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Sowp\ArticleBundle\Entity\Article;

class LoadArticleData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $users = $manager->getRepository(User::class)->findAll() + [];

        for ($i = 0; $i < 100; ++$i) {
            $article = new Article();
            $article->setTitle($faker->text(255));
            $article->setContent($faker->paragraph(20));
            $article->setCreatedBy($faker->randomElement($users));
            $article->setModifitedBy($faker->randomElement($users));
            $article->setCreatedAt($faker->dateTimeBetween('-5 years', 'now'));
            $article->setEditNote($faker->text());
            $manager->persist($article);
        }

        $manager->flush();
    }
}
