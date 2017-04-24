<?php

namespace Sowp\ApiBundle\Tests\Controller;



use Doctrine\ORM\EntityManager;
use Faker\Factory;
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
     * @var Faker
     */
    private $faker;
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->faker = Factory::create('de_DE');
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
    public function testAddtAction()
    {

        $collection = new Collection();
        $collection->setTitle($this->faker->words(3, 5));
        $collection->setPublic(true);

        /**
         * @var Response
         */
        $response =  $this->client->post('http://jakowaty.pl/api/v1/collections/add', [
            'body' => $this->container->get('jms_serializer')->serialize($collection, 'json')
        ]);

        $json = $response->getBody();
        $this->assertEquals(201, $response->getStatusCode());
    }
}