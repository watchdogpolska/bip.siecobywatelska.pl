<?php

namespace Sowp\ApiBundle\Tests\Controller;



use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Faker\Factory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Sowp\CollectionBundle\Entity\Collection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ApiCollectionControllerTest
 * @package Sowp\ApiBundle\Tests\Controller
 */
class ApiCollectionControllerTest extends WebTestCase
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

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->faker = Factory::create();
        $this->container = self::$kernel->getContainer();
        $this->em = $this->container->get('Doctrine')->getManager();
        $this->client = new Client([
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    public function testShowAction()
    {
        $response = $this->client->get('http://jakowaty.pl/api/v1/collections/41');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testErrorResponse()
    {

    }
}