<?php
namespace AppBundle\Tests\ApiUtils;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use GuzzleHttp\Client;
use Sowp\ApiBundle\Service\ApiHelper;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Client as SymfonyClient;

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
            ],
            'auth' => [
                'root',
                'root'
            ]
        ]);
    }

    /**
     * @return Article
     */
    public function createArticle()
    {
        $a = new Article();
        $a->setTitle($this->createItemTitle());
        $a->setContent($this->faker->realText(3000));
        $a->setCreatedAt(new \DateTime());
        $a->setEditNote("Article created for automatic tests.");
        $this->em->persist($a);
        $this->em->flush($a);
        $this->putEntityToTrash($a);
        return $a;
    }

    /**
     * @return Collection
     */
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

    /**
     * @return News
     */
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

    /**
     * @return string
     */
    private function createItemTitle()
    {
        /**
         * @TODO: clean here
         */
        $this->faker->title; // just a test
        return "Test " . (string)\time() . uniqid();
    }

    /**
     * @param $e News|Collection|Article
     * @return int|null
     */
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
                $this->em->flush();
            }
        }
    }

    /**
     * @param $needle string
     * @param $haystack string
     * @param bool $ignoreCase
     * @return bool
     */
    public function apiStringContains($needle, $haystack, $ignoreCase = false) : bool
    {
        $d = '#';
        $needle = \preg_quote($needle, $d);
        $pattern = $d.$needle.$d;

        if ($ignoreCase) {
            $pattern .= 'i';
        }

        return (preg_match($pattern, $haystack) > 0) ? true : false;
    }

    public function assertArrayPropertyExists($key, array $array)
    {
        return $this->assertTrue(array_key_exists($key, $array), "Not in array $key");
    }

    /**
     * @return ApiHelper
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return SymfonyClient
     */
    public function createAuthClient()
    {
        //as user defined in LoadUserData Fixtures
        return static::createClient([],[
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ]);
    }

    /**
     * @param string $route
     * @param array $params
     * @param bool $absolute
     * @return string
     */
    public function generateUrl(string $route, array $params = [], bool $absolute = true)
    {
        return $this
            ->container
            ->get('router')
            ->generate(
                $route,
                $params,
                $absolute ? Router::ABSOLUTE_PATH : Router::RELATIVE_PATH
            );
    }

    /**
     * @param SymfonyClient $client
     * @return int
     */
    public static function httpCode(SymfonyClient $client)
    {
        return $client->getResponse()->getStatusCode();
    }
}