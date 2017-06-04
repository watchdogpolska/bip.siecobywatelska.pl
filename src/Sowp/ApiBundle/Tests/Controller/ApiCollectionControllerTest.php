<?php

namespace Sowp\ApiBundle\Tests\Controller;



use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Faker\Factory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Sowp\ApiBundle\Response\ApiResponse;
use Sowp\ApiBundle\Tests\ApiUtils\ApiTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ApiCollectionControllerTest
 * @package Sowp\ApiBundle\Tests\Controller
 */
class ApiCollectionControllerTest extends ApiTestCase
{
    protected $host = false;

    public function setUp()
    {
        parent::setUp();

        //exported enviroment var
        //$ export PHP_SERVER_NAME="http://your-server-name.com/"
        $this->host = \getenv('PHP_SERVER_NAME');
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->trashCollect();
    }

    public function testShowAction()
    {
        //create random Collection
        $c = $this->createCollection();

        // get its relative path link,
        // from console I get http://localhost/
        $link = $this->helper->getShowLinkForEntity($c, false);

        if (!$this->host) {
            $this->assertTrue(
                false,
                "'PHP_SERVER_NAME' env variable must be set with hostname"
            );
        }

        //request with client to concatenated addr + link
        $response = $this->client->get($this->host . $link);

        //read body
        $body = $response->getBody();

        //status code
        $this->assertEquals(200, $response->getStatusCode());

        //try derserialize
        $c_ds = $this
            ->container
            ->get('jms_serializer')
            ->deserialize($body, ApiResponse::class, 'json');

        //assert deserialized var is object
        $this->assertEquals(true, \is_object($c_ds));
    }

    public function testListAction()
    {
        if (!$this->host) {
            $this->assertTrue(
                false,
                "'PHP_SERVER_NAME' env variable must be set with hostname"
            );
        }

//        $response = $this->client->get('');
    }
}