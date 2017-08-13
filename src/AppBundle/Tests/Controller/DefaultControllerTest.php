<?php
namespace AppBundle\Tests\Controller;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testIndexAction()
    {
        $client = static::createClient();
        $link = $this->generateUrl('homepage');
        $client->request('GET', $link);

        $this->assertEquals(
            Response::HTTP_OK,
            self::httpCode($client),
            "Expected response code 200"
        );
    }
}