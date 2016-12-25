<?php

namespace Sowp\NewsModuleBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\NewsModuleBundle\Entity\NewsRepository;
use Sowp\NewsModuleBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\CollectionRepository;

class NewsControllerTest extends WebTestCase
{
    /*
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/news/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /news/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'sowp_newsmodulebundle_news[field_name]'  => 'Test',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'sowp_newsmodulebundle_news[field_name]'  => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

    */
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /** @var  Sowp\NewsmoduleBunlde\Entity\NewsRepository */
    private $news_R;

    /** @var  Sowp\NewsmoduleBunlde\Entity\CategoryRepository */
    private $cat_R;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->news_R = $this->em->getRepository('NewsModuleBundle:News');
        $this->cat_R = $this->em->getRepository('NewsModuleBundle:Collection');
    }

    public function testIndexHeader()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/wiadomosci/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("News list")')->count(),
            'HTML doesn\'t contain proper heading'
        );
    }

    public function testIndexMessageStructure()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/wiadomosci/');


            /*->getMockBuilder(NewsRepository::class)
            ->disableOriginalConstructor()
            ->getMock();*/

        //var_dump($crawler);

    }
}
