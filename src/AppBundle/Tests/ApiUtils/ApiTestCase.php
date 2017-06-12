<?php
namespace AppBundle\Tests\ApiUtils;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use GuzzleHttp\Client;
use Sowp\ApiBundle\Service\ApiHelper;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
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
        $c->setTitle($this->createItemTitle());
        $c->setPublic(true);
        $this->em->persist($c);
        $this->em->flush($c);
        $this->putEntityToTrash($c);
        return $c;
    }

    public function createNews()
    {
        $n = new News();
        $n->setTitle($this->createItemTitle());
        $n->setPinned(true);
        $n->setContent($this->faker->realText(3000));
        $this->em->persist($n);
        $this->em->flush($n);
        $this->putEntityToTrash($n);
        return $n;
    }

    private function createItemTitle()
    {
        return "Test " . (string)\time();
    }

    public function putEntityToTrash($e)
    {
        $this->trash[] = $e->getId();
    }

    public function trashCollect($class)
    {
        $r = $this->em->getRepository($class);

        foreach ($this->trash as $trash) {
            $e = $r->find($trash);
            if (\is_object($e)) {
                $this->em->remove($e);
            }
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

    public function assertArrayPropertyExists($key, array $array)
    {
        return $this->assertTrue(array_key_exists($key, $array), "Not in array $key");
    }
}