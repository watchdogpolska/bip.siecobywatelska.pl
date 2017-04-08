<?php

namespace Sowp\ApiBundle\Tests\Controller;



use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Sowp\CollectionBundle\Entity\Collection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class CollectionControllerTest
 * @package Sowp\ApiBundle\Tests\Controller
 */
class CollectionControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var
     */
    private $faker;
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->em = $this->container->get('Doctrine')->getManager();
        $this->client = new Client([
            'base_url' => 'http://jakowaty.pl',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    /**
     *
     */
    public function testPostAction()
    {
        $collection = new Collection();
        $collection->setTitle("");
        $this
            ->container
            ->get('jms_serializer');
        /**
         * @var Response
         */
        $response =  $this->client->post('http://jakowaty.pl/api/v1/collections/add', [
            'body' => \GuzzleHttp\json_encode([1,23,3])
        ]);

        $json = $response->getBody();
        $this->assertEquals(201, $response->getStatusCode());
    }
}