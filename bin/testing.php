#!/usr/bin/env php
<?php
$loader = require __DIR__.'/../app/autoload.php';
$this->setKernel(new AppKernel('dev', true));
$this->getKernel()->boot();

//
//class Testing extends PHPUnit_Framework_TestCase {
//
//    /**
//     * @var AppKernel
//     */
//    private $kernel;
//
//    /**
//     * @var \Symfony\Component\DependencyInjection\ContainerInterface
//     */
//    private $container;
//
//    /**
//     * @var \Doctrine\ORM\ObjectManager|object
//     */
//    private $em;
//
//    /**
//     * @var \GuzzleHttp\Client
//     */
//    private $client;
//
//    /**
//     * @var \Symfony\Component\Console\Output\StreamOutput
//     */
//    private $output;
//
//    /**
//     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
//     */
//    private $router;
//
//    /**
//     * @var Faker
//     */
//    private $faker;
//
//    public function __construct($in_debug = true)
//    {
//        $this->setKernel(new AppKernel('dev', true));
//        $this->getKernel()->boot();
//        $this->setContainer($this->getKernel()->getContainer());
//        $this->setEm($this->getContainer()->get('doctrine')->getManager());
//        $this->setRouter($this->getContainer()->get('router'));
//        $this->setFaker(\Faker\Factory::create('de_DE'));
//
//        $this->setOutput(
//                new Symfony\Component\Console\Output\StreamOutput(
//                    \fopen('php://stdout', 'w')
//                )
//        );
//
//        $this->setClient(
//            new \GuzzleHttp\Client([
//              //  'base_url' => 'http://jakowaty.pl',
//                'exceptions' => false,
//                'cookies' => true
//            ])
//        );
//
//        if ($in_debug) {
//
//        }
//    }
//
//    public function test()
//    {
//        try {
//            $this->testAddCollection();
//        } catch (\Exception $e) {
//            throw $e;
//        }
//    }
//
//    private function testAddCollection()
//    {
//        $this->writelnInfo( __FUNCTION__);
//        $this->writelnInfo('creating collection...');
//        $collection_json = $this
//            ->getContainer()
//            ->get('jms_serializer')
//            ->serialize($this->createRandomCollection(), 'json');
//
//        print_r($collection_json . "\n");
//        /**
//         * @var \GuzzleHttp\Psr7\Response
//         */
//        $response = $this
//            ->getClient()
//            ->request(
//                'POST',
//                'http://jakowaty.pl' . $this->getRouter()->generate('api_collection_add'),
//                [
//                    'cookies' => $this->getCookieJar(),
//                    'body' => $collection_json
//                ]
//            );
//
//        //debug of client
//        print_r($response->getBody()->getContents());
//
//        $this->assertEquals(201, $response->getStatusCode(), "Response code should be 201");
//
//    }
//
//    private function createRandomCollection() : \Sowp\CollectionBundle\Entity\Collection
//    {
//        $collection = new \Sowp\CollectionBundle\Entity\Collection();
//        $collection->setTitle($this->getFaker()->words(3,5));
//        $collection->setPublic(true);
//        return $collection;
//    }
//
//    private function getCookieJar() : \GuzzleHttp\Cookie\CookieJar
//    {
//        return $this
//            ->getClient()
//            ->getConfig()['cookies']
//            ->fromArray(
//                ['XDEBUG_SESSION' => 'PHPSTORM'],
//                'jakowaty.pl'
//            );
//    }
//
//    private function writelnError($string) : Testing
//    {
//        $this->getOutput()->writeln('<error>'. $string .'</error>');
//        return $this;
//    }
//
//    private function writelnInfo($string) : Testing
//    {
//        $this->getOutput()->writeln('<info>'. $string .'</info>');
//        return $this;
//    }
//
//    /**
//     * @return AppKernel
//     */
//    public function getKernel(): AppKernel
//    {
//        return $this->kernel;
//    }
//
//    /**
//     * @param AppKernel $kernel
//     */
//    public function setKernel(AppKernel $kernel)
//    {
//        $this->kernel = $kernel;
//    }
//
//    /**
//     * @return \Symfony\Component\DependencyInjection\ContainerInterface
//     */
//    public function getContainer(): \Symfony\Component\DependencyInjection\ContainerInterface
//    {
//        return $this->container;
//    }
//
//    /**
//     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
//     */
//    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container)
//    {
//        $this->container = $container;
//    }
//
//    /**
//     * @return \Doctrine\ORM\EntityManager|object
//     */
//    public function getEm()
//    {
//        return $this->em;
//    }
//
//    /**
//     * @param \Doctrine\ORM\EntityManager|object $em
//     */
//    public function setEm($em)
//    {
//        $this->em = $em;
//    }
//
//    /**
//     * @return \GuzzleHttp\Client
//     */
//    public function getClient(): \GuzzleHttp\Client
//    {
//        return $this->client;
//    }
//
//    /**
//     * @param \GuzzleHttp\Client $client
//     */
//    public function setClient(\GuzzleHttp\Client $client)
//    {
//        $this->client = $client;
//    }
//
//    /**
//     * @return \Symfony\Component\Console\Output\StreamOutput
//     */
//    public function getOutput(): \Symfony\Component\Console\Output\StreamOutput
//    {
//        return $this->output;
//    }
//
//    /**
//     * @param \Symfony\Component\Console\Output\StreamOutput $output
//     */
//    public function setOutput(\Symfony\Component\Console\Output\StreamOutput $output)
//    {
//        $this->output = $output;
//    }
//
//    /**
//     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
//     */
//    public function getRouter(): \Symfony\Bundle\FrameworkBundle\Routing\Router
//    {
//        return $this->router;
//    }
//
//    /**
//     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
//     */
//    public function setRouter(\Symfony\Bundle\FrameworkBundle\Routing\Router $router)
//    {
//        $this->router = $router;
//    }
//
//    /**
//     * @return Faker
//     */
//    public function getFaker()
//    {
//        return $this->faker;
//    }
//
//    /**
//     * @param Faker $faker
//     */
//    public function setFaker($faker)
//    {
//        $this->faker = $faker;
//    }
//}
//
//$testing = new Testing();
//$testing->test();