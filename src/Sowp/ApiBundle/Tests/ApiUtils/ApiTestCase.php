<?php
namespace Sowp\ApiBundle\Tests\ApiUtils;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use GuzzleHttp\Client;
use Sowp\ApiBundle\Service\ApiHelper;
use Sowp\CollectionBundle\Entity\Collection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var Faker\Faker
     */
    protected $faker;

    /**
     * @var ApiHelper
     */
    protected $helper;

    /**
     * @var array
     */
    protected $trash = [];

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->faker = Factory::create();
        $this->container = self::$kernel->getContainer();
        $this->em = $this->container->get('Doctrine')->getManager();
        $this->helper = $this->container->get('api_helper');
        $this->client = new Client([
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    public function createCollection()
    {
        $c = new Collection();
        $c->setCreatedAt(new \DateTime());
        $c->setTitle("Test " . (string)\time());
        $c->setPublic(true);
        $this->em->persist($c);
        $this->em->flush($c);
        $this->putEntityToTrash($c);
        return $c;
    }

    public function putEntityToTrash($e)
    {
        $this->trash[] = $e;
    }

    public function trashCollect()
    {
        foreach ($this->trash as $trash) {
            $this->em->remove($trash);
        }

        $this->em->flush();
    }

    public function apiStringContains($needle, $haystack, $ignoreCase = false) : bool
    {
        if ($this->ignoreCase) {
            return stripos($needle, $haystack) !== false;
        } else {
            return strpos($needle, $haystack) !== false;
        }
    }
}