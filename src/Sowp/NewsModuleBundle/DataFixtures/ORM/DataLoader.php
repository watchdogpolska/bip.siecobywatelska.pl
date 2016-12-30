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
            $col->setTitle($faker->words(mt_rand(3, 8), true));
            $col->setPublic(true);
            $col->setCreatedAt($faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now', $timezone = date_default_timezone_get()));
            $col->setCreatedBy($users[array_rand($users)]);
            $om->persist($col);
            $coll[] = $col;
        }

        for ($x = 10; $x > 0; --$x) {
            $news = new News();
            $news->setTitle($faker->words(mt_rand(3, 8), true));
            $r = mt_rand(1, 14);
            while (($newsCol = $news->getCollections()) && $newsCol->count() < $r) {
                $col = $coll[array_rand($coll)];
                if (!$newsCol->contains($col)) {
                    $news->addCollection($col);
                }
            }
            $news->setContent($faker->text(5000));
            $news->setPinned($faker->boolean(30));
            $col->setCreatedAt($faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now', $timezone = date_default_timezone_get()));
            $col->setCreatedBy($users[array_rand($users)]);
            $om->persist($news);
        }
        $om->flush();
    }
}
