<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('root');
        $userAdmin->setPlainPassword('root');
        $userAdmin->setEmail('root@example.org');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(array('ROLE_SUPER_ADMIN'));

        $manager->persist($userAdmin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
