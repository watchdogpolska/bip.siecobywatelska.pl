<?php
namespace Sowp\NewsModuleBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
/**
 * @class DataLoader extends AbstaractFixture
 */
class DataLoader extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        $faker = \Faker\Factory::create();
        $collections = $om->getRepository(Collection::class)->findAll();
        for ($i = 0; $i < 100; $i++) {
            $news = new News();
            $news->setTitle($faker->words(mt_rand(3, 8), true));
            foreach ($faker->randomElements($collections, $faker->numberBetween(1, 6)) as $collection) {
                $news->addCollection($collection);
            }
            $news->setContent($faker->text(5000));
            $news->setPinned($faker->boolean(30));
            $om->persist($news);
            $om->flush();
        }
    }
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}