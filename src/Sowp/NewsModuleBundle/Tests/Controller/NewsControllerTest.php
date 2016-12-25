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

    /**
     *  response and page header
     */
    public function testIndexHeader()
    {
        print __FUNCTION__ . "\n";
        $client = $this->createClient();
        $crawler = $client->request('GET', '/wiadomosci/');

        $this>self::assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("News list")')->count(),
            'HTML doesn\'t contain proper heading'
        );
    }

    /**
     * structure of news index
     */
    public function testIndexMessageStructure()
    {
        print __FUNCTION__ . "\n";
        $client = $this->createClient();
        $crawler = $client->request('GET', '/wiadomosci/');
        $count = (int)$this->news_R->getTotalNewsCount();
        $divs = $crawler->filter('div.news-entry');
        $divs_c = $divs->count();

        if ($count > 0) {
            $this->assertGreaterThan(0, $divs->count(), "Count of News objects is greater than 0,
                                                         but there are no corresponding DOM elements");

            foreach ($divs as $child) {

                /** @var $child \DOMElement */
                $this->assertEquals(
                    1, $child->getElementsByTagName('table')->length,
                    "No '<table>' element found in iteration throug div.art-entry"
                );

            }

            $id_c = $divs->filter('td.id')->count();
            $attachments_c = $divs->filter("td.attachments")->count();

            $this->assertEquals($divs_c, $id_c, "Inapropriate structure - lacking td.id");
            $this->assertEquals($divs_c, $attachments_c, "Inapropriate structure - lacking td.attachments");
        } else {
            $this->assertEquals(0, $divs->count());
        }
    }

    public function testAddNews()
    {

    }

    public function testCollectionRepository()
    {
        $reflection = new \ReflectionObject($this->cat_R);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
    }

    public function testNewsRepository()
    {
        $reflection = new \ReflectionObject($this->news_R);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
    }
}
