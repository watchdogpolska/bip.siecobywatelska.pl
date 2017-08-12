<?php

namespace Sowp\NewsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsBundle\Entity\News;

/**
 * @class DataLoader extends AbstaractFixture
 */
class DataLoader extends AbstractFixture
{
    public function load(ObjectManager $om)
    {
        $faker = \Faker\Factory::create();
	    $users = $om->getRepository('AppBundle\Entity\User')->findAll();
		$all_collections = $om->getRepository( Collection::class)->findAll();

        for ($x = 0; $x < 10; $x++) {
            $news = new News();
            $news->setTitle($faker->words(mt_rand(3, 8), true));

            $nb_collection = $faker->numberBetween(1, 6);
            foreach ($faker->randomElements($all_collections, $nb_collection) as $collection) {
                $news->addCollection($collection);
            }

            $news->setContent($faker->text(5000));
            $news->setPinned($faker->boolean(30));
            $om->persist($news);
        }
        $om->flush();
    }
}
