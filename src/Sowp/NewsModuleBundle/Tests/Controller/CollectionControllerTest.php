<?php

namespace NewsModuleBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CollectionControllerTest extends WebTestCase
{
    /** @var $em EntityManager */
    private $em;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIndex()
    {
        echo __FUNCTION__."\n";
        $client = $this->createClient();
        $crawler = $client->request('GET', '/kolekcje/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Request should return HTTP 200');

        $this->assertEquals(1,
            $crawler->filter('html:contains("Collection list")')->count(),
            "HTML doesn't contain proper heading");
    }

    public function testCollectionStructure()
    {
        echo __FUNCTION__."\n";
        $collectionRepository = $this->em->getRepository('NewsModuleBundle:Collection');
        $slugs = $collectionRepository->getCollectionsSlugs();

        if (count($slugs) > 0) {
            $slug = $slugs[array_rand($slugs)]['slug'];
            $client = $this->createClient();
            $crawler = $client->request('GET', sprintf('/kolekcje/%s', $slug));

            $this->assertEquals(200, $client->getResponse()->getStatusCode(),
                'Request to collection_show should return HTTP 200');

            $this->assertEquals(1, $crawler->filter('h1')->count(),
                'Collection show page should contain exactly one <h1>');

            $this->assertEquals('Info',
                $crawler->filter('h2.panel-title')->first()->text(),
                'First h2.panel-title contains invalid text'
            );

            $this->assertEquals(1,
                $crawler->filter('table.table')->count(),
                'There should be one table in HTML');
        }
    }

    public function testAddCollection()
    {
        echo __FUNCTION__."\n";
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/kolekcje/dodaj');
        $faker = \Faker\Factory::create();

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Route should return HTTP 200');

        $form = $crawler->selectButton('add_collection')->form();

        foreach ($form->all() as $input) {
            $field_name = $input->getName();

            switch ($field_name) {
                case \preg_match('#public#', $field_name) === 1:
                    $form[$field_name] = 1;
                    break;
                case \preg_match('#title|Title#', $field_name) === 1:
                    $form[$field_name] = $faker->text(150);
                    break;
            }
        }
        $crawler2 = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Route should return HTTP 200');

        $this->assertRegExp('#Dodano kolekcję|Wystąpił błąd#',
            $crawler2->filter('div.alert')->first()->text(),
            'No necessary flash-text shown');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
