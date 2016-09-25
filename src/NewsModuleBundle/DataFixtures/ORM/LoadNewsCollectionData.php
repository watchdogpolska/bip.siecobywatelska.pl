<?php
namespace NewsModuleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture           as AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface   as OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager              as ObjectManager;
use AppBundle\Entity\User                                  as User;
use NewsModuleBundle\Entity\Collection                     as NewsCollection;
use NewsModuleBundle\Entity\News                           as News;

class LoadNewsCollectionData extends AbstractFixture implements OrderedFixtureInterface
{
   
    public function load(ObjectManager $om)
    {
        for ($x = 0; $x < 20; $x++) {
            $col                = new NewsCollection();
            $foreignRefCallUser = 'user' . mt_rand(0, 9);
            $refCall            = "collection{$x}";
            
            $col->setTitle("Important collection {$x}");
            $col->setPublic(true);
            $col->setCreatedAt(new \DateTime());
            $col->setCreatedBy($this->getReference($foreignRefCallUser));

            $om->persist($col);
            $om->flush();

            $this->addReference($refCall, $col);
        }
    }


    public function getOrder()
    {
        return 2;
    }
}