<?php

namespace Sowp\CollectionBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Sowp\CollectionBundle\Entity\Collection;

/**
 * Class DataLoader
 * @package Sowp\CollectionBundle\DataFixtures\ORM
 */
class DataLoader extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $collections = [];

        for ($x = 0; $x < 20; $x++) {

            $collection = new Collection();
            $collection->setPublic(true);
            $collection->setTitle($faker->words(4, true));

            $manager->persist($collection);
            $collections[] = $collection;
        }

        $manager->flush();

    }
}