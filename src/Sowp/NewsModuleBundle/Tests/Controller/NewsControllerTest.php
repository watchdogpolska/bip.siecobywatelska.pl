<?php

namespace Sowp\NewsModuleBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\NewsModuleBundle\Entity\Collection;

class NewsControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /** @var Sowp\NewsmoduleBunlde\Entity\NewsRepository */
    private $news_R;

    /** @var Sowp\NewsmoduleBunlde\Entity\CategoryRepository */
    private $cat_R;

    /**
     * {@inheritdoc}
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
     *  response and page header.
     */
    public function testIndexHeader()
    {
        echo __FUNCTION__."\n";
        $client = $this->createClient();
        $crawler = $client->request('GET', '/wiadomosci/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("News list")')->count(),
            'HTML doesn\'t contain proper heading'
        );
    }

    /**
     * structure of news index.
     */
    public function testIndexMessageStructure()
    {
        echo __FUNCTION__."\n";
        $client = $this->createClient();
        $crawler = $client->request('GET', '/wiadomosci/');
        $count = (int) $this->news_R->getTotalNewsCount();
        $divs = $crawler->filter('div.news-entry');
        $divs_c = $divs->count();

        if ($count > 0) {
            $this->assertGreaterThan(0, $divs->count(), 'Count of News objects is greater than 0,
                                                         but there are no corresponding DOM elements');

            foreach ($divs as $child) {
                /* @var $child \DOMElement */
                $this->assertEquals(
                    1, $child->getElementsByTagName('table')->length,
                    "No '<table>' element found in iteration throug div.art-entry"
                );
            }

            $id_c = $divs->filter('td.id')->count();
            $title_c = $divs->filter('h3.panel-title')->count();

            $this->assertEquals($divs_c, $id_c, 'Inapropriate structure - lacking td.id');
            $this->assertEquals($divs_c, $title_c, 'Inapropriate structure - lacking h3.panel-title');
        } else {
            $this->assertEquals(0, $divs->count());
        }
    }

    public function testAddNews()
    {
        echo __FUNCTION__."\n";
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/wiadomosci/dodaj');
        $form = $crawler->selectButton('send_new_message')->form();
        $coll_ids = [];
        $faker = \Faker\Factory::create();

        foreach ($this->cat_R->getCollectionsIds() as $id) {
            $coll_ids[] = $id['id'];
        }

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
                            "Route '/wiadomosci/dodaj' should return HTTP 200");

        foreach ($form->all() as $input) {
            $field_name = $input->getName();
            switch ($field_name) {
                case \preg_match('#title#', $field_name) === 1:
                    $form[$field_name] = 'Testing title at '.\time();
                    break;
                case preg_match('#content#', $field_name) === 1:
                    $str = '';
                    $x1 = \rand(10, 500);
                    for ($x = $x1; $x >= 0; --$x) {
                        $str .= $faker->text(mt_rand(10, 150));
                    }
                    $form[$field_name] = $str;
                    break;
                case preg_match('#pinned#', $field_name) === 1:
                    $form[$field_name] = 1;
                    break;
                case preg_match('#collections#', $field_name) === 1:
                    break;
                default:
                    break;
            }
        }

        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Route should return HTTP 200');

        $x = false;
        if (
            ($crawler->filter('html:contains("Dodano")')->count() > 0) ||
            ($crawler->filter('html:contains("niepoprawne,")')->count() > 0)
        ) {
            $x = true;
        }

        $this->assertTrue($x, 'Client submited form properly, response code OK, but response HTML structure');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->news_R = null;
        $this->cat_R = null;
        $this->em->close();
        $this->em = null;
    }
}
