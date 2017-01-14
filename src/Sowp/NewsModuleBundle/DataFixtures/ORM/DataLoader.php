<?php

namespace Sowp\NewsModuleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Sowp\NewsModuleBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;

/**
 * @class DataLoader extends AbstaractFixture
 */
class DataLoader extends AbstractFixture
{
    public function load(ObjectManager $om)
    {
        $faker = \Faker\Factory::create();


        $userRepo = $om->getRepository('AppBundle\Entity\User');
        $users = $userRepo->findAll();
        $coll = [];

        for ($x = 20; $x > 0; --$x) {
            $col = new Collection();
            $col->setTitle($faker->words(5, true));
            $col->setPublic(true);
            $col->setCreatedAt($faker->dateTimeBetween());
            $col->setCreatedBy($faker->randomElement($users));
            $om->persist($col);
            $coll[] = $col;
        }

        for ($x = 10; $x > 0; --$x) {
            $news = new News();
            $news->setTitle($faker->words(mt_rand(3, 8), true));
            $nb_collection = $faker->numberBetween(1, 6);

            foreach ($faker->randomElements($coll, $nb_collection) as $collection) {
                $news->addCollection($collection);
            }

            $news->setContent($faker->text(5000));
            $news->setPinned($faker->boolean(30));
            $om->persist($news);
        }
        $om->flush();
    }
}
