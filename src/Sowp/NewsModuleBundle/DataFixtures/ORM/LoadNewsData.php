<?php

namespace Sowp\NewsModuleBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture as AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface as OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use AppBundle\Entity\User as User;
use Sowp\NewsModuleBundle\Entity\News as News;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        for ($x = 0; $x < 100; ++$x) {
            $oneNews = new News();
            $date = new \DateTime();
            $userRef = 'user'.mt_rand(0, 9);
            $col1Ref = 'collection'.mt_rand(0, 19);

            $date->modify("- {$x} days");

            do {
                $col2Ref = 'collection'.mt_rand(0, 19);
            } while ($col1Ref === $col2Ref);

            $oneNews->setTitle('Ciekawy tytuł urzędowy '.$x);
            $oneNews->addCollection($this->getReference($col1Ref));
            $oneNews->addCollection($this->getReference($col2Ref));
            $oneNews->setContent($this->generateContent());
            $oneNews->setPinned($x % 6 === 0 ? true : false);

            $oneNews->setCreatedAt($date);
            $oneNews->setCreatedBy($this->getReference($userRef));

            $om->persist($oneNews);
            $om->flush();
        }
    }

    public function getOrder()
    {
        return 3;
    }

        // =)
    private function generateContent()
    {
        $content = '';
        $phrases = [
            'labamba',
            'bamboleo',
            'koko dżambo',
            'trala la la',
            'lorem ipzzuum',
            'trutu tu tu',
            'bermembwe',
            'dolor',
            'amet',
        ];

        for ($x = 10; $x >= 0; --$x) {
            for ($y = 20; $y >= 0; --$y) {
                $content .= $phrases[array_rand($phrases)].' ';
            }

            if ($x % 2 === 0) {
                $content .= '<br>';
            }
        }

        return $content;
    }
}
