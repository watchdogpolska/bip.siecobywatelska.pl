<?php

namespace Sowp\NewsModuleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Sowp\NewsModuleBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
//use Faker\Factory;

/**
 * @class DataLoader extends AbstaractFixture
 *
 * repeating shot data loader, loads one user,
 * two collections added by this user, and 5 articles
 * each collection....
 */
class DataLoader extends AbstractFixture
{
    public function load(ObjectManager $om)
    {
        $faker = \Faker\Factory::create('cs_CZ');
        $date = function () {
            $minTime = strtotime('2014-01-01 00:00:01');
            $maxTime = strtotime('2016-09-01 00:00:01');
            $randTime = mt_rand($minTime, $maxTime);
            $dt = new \DateTime();
            $dt->setTimestamp($randTime);
            return $dt;
        };
        $user = new User();
        $coll = [];

        $user->setUsername($faker->name);
        $user->setPlainPassword($faker->password);
        $user->setEmail($faker->email);
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_SUPER_ADMIN'));
        $om->persist($user);

        for ($x = 2; $x > 0; $x--) {
            $col = new Collection();
            $col->setTitle($faker->words(mt_rand(3, 8), true));
            $col->setPublic(true);
            $col->setCreatedAt($date());
            $col->setCreatedBy($user);
            $om->persist($col);
            $coll[] = $col;
        }

        for ($x = 10; $x > 0; $x--) {
            $news = new News();
            $news->setTitle($faker->words(mt_rand(3, 8), true));
            $news->addCollection($coll[0]);
            $news->addCollection($coll[1]);
            $news->setContent($faker->text(5000));
            $news->setPinned(mt_rand(1, 100) % 3 === 0 ? true : false);
            $news->setCreatedAt($date());
            $news->setCreatedBy($user);
        }
    }
}
