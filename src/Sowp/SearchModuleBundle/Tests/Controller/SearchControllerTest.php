<?php

namespace Sowp\SearchModuleBundle\Tests;

use AppBundle\Tests\ApiUtils\ApiTestCase;

class SearchControllerTest extends ApiTestCase
{
    /** @var  string|null $host */
    protected $host;

    /** @var Router $router */
    protected $router;

    public function setUp()
    {
        parent::setUp();

        $this->host = \rtrim($this->container->getParameter('php_server_name'), '/');
        $this->router = $this->container->get('router');
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testSearchAction()
    {
        $a = $this->createArticle();
        $n = $this->createNews();
        $client = static::createClient();
        $crawler = $client->request(
            'GET',
            $this->router->generate('sowp_searchbundle_search'),
            [
                'q' => 'Test'
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Response code should be 200");
        $this->assertTrue(
            $this->apiStringContains(
                'Wynik wyszukiwania "Test"',
                $client->getResponse()->getContent()
            ),
            "Not Found seeked text from template"
        );

        $this->assertGreaterThan(1, $crawler->filter('h2.search-result')->count());
    }
}