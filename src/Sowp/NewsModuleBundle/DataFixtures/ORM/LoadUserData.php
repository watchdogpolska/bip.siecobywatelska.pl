<?php

namespace Sowp\NewsModuleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture as AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface as OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use AppBundle\Entity\User as User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        for ($x = 0; $x < 10; ++$x) {
            $leUser = new User();
            $leUser->setUsername('user-'.$x);
            $leUser->setPlainPassword('qwerty123');
            $leUser->setEmail('root'.$x.'@example.org');
            $leUser->setEnabled(true);
            $leUser->setRoles(array('ROLE_SUPER_ADMIN'));

            $om->persist($leUser);
            $om->flush();

            $refCall = 'user'.$x;
            $this->addReference($refCall, $leUser);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
