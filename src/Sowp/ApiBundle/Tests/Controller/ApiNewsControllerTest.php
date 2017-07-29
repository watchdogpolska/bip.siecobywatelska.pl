<?php
namespace Sowp\ApiBundle\Tests\Controller;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ApiNewsControllerTest extends ApiTestCase
{
    protected $host = false;

    public function setUp()
    {
        parent::setUp();

        //exported enviroment var
        //$ export PHP_SERVER_NAME="http://your-server-name.com/"
        //with last "/"
        $this->host = \getenv('PHP_SERVER_NAME');
        $this->container->get('app_bundle.fixtures_loader')->addAll();
        $this->container->get('app_bundle.fixtures_loader')->loadAllFromQueue();
    }

    protected function tearDown()
    {
        $this->trashCollect(News::class);
    }

    public function testShowAction()
    {
        $n = $this->createNews();
        $title = $n->getTitle();

        // get its relative path link,
        // from console I get http://localhost/
        //with last "/"
        $link = $this->helper->getShowLinkForEntity($n, false);

        if (!$this->host) {
            $this->assertTrue(
                false,
                "'PHP_SERVER_NAME' env variable must be set with hostname"
            );
        }

        //request with client to concatenated addr + link
        $response = $this->client->get($this->host . $link);

        /**
         * @var Stream $body
         */
        $body = $response->getBody()->getContents();

        //status code
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($body, "Response Body should be JSON format");

        //try derserialize
        $c_ds = \json_decode($body, true);

        //assert deserialized var is object
        $this->assertEquals(true, \is_array($c_ds), "Deserialized fail");

        $fromJsonTitle = $c_ds['data']['title'];

        $this->assertArrayPropertyExists('response_code', $c_ds);
        $this->assertArrayPropertyExists('links', $c_ds);
        $this->assertEquals($title, $fromJsonTitle, "Title from object is not the same as from api");
    }

    public function testListAction()
    {
        if (!$this->host) {
            $this->assertTrue(
                false,
                "'PHP_SERVER_NAME' env variable must be set with hostname"
            );
        }

        $link = $this->container->get('router')->generate('api_news_list', [], Router::RELATIVE_PATH);

        try {
            $count = $this->em->getRepository(News::class)->getTotalNewsCount();
            // 100 - comes from fixtures
            $this->assertEquals(100, $count, "Wrong count of news");
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage(), "Problem during articles retrieval.");
        }

        $response = $this->client->get($this->host.$link);
        $body = $response->getBody()->getContents();
        $cc_ds = \json_decode($body, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($body, "Response Body should be JSON format");
        $this->assertEquals(true, \is_array($cc_ds), "Deserialized fail");
        $this->assertArrayPropertyExists('response_code', $cc_ds);
        $this->assertArrayPropertyExists('links', $cc_ds);
        $this->assertArrayPropertyExists('data', $cc_ds);

        $page1Count = \count($cc_ds['data']);

        //we have 2 pages
        $this->assertEquals(10, $page1Count, "Data count should be 10");

        foreach ($cc_ds['links'] as $l) {
            if ($l['rel'] === 'next') {
                $nextLink = $l['href'];
                break;
            }
        }

        $this->assertTrue(isset($nextLink), "No next link at page 1");

        if (isset($nextLink)) {
            $response2 = $this->client->get($nextLink);
            $body2 = $response2->getBody()->getContents();
            $cc_ds2 = \json_decode($body2, true);

            $this->assertEquals(200, $response2->getStatusCode());
            $this->assertJson($body2, "Response Body should be JSON format");
            $this->assertEquals(true, \is_array($cc_ds2), "Deserialized fail");
            $this->assertArrayPropertyExists('response_code', $cc_ds2);
            $this->assertArrayPropertyExists('links', $cc_ds2);
            $this->assertArrayPropertyExists('data', $cc_ds2);

            $page2Count = \count($cc_ds2['data']);

            $this->assertEquals($page1Count, $page2Count, "Count on both pages should be the same");

            foreach ($cc_ds2['links'] as $l) {
                if ($l['rel'] === 'previous') {
                    $previous = true;
                }
            }

            $this->assertTrue(
                isset($previous) && $previous,
                "No 'previous' link set"
            );
        }

    }
}