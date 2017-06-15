<?php
namespace Sowp\ApiBundle\Tests\Controller;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\ArticleBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ApiArticleControllerTest extends ApiTestCase
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
        $this->trashCollect(Article::class);
    }

    public function testShowAction()
    {
        $a = $this->createArticle();

        // get its relative path link,
        // from console I get http://localhost/
        //with last "/"
        $link = $this->helper->getShowLinkForEntity($a, false);

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

        $this->assertArrayPropertyExists('response_code', $c_ds);

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
            $count = $this
                ->em
                ->getRepository(Article::class)
                ->createQueryBuilder('art')
                ->select('COUNT(art.id)')
                ->getQuery()
                ->getSingleScalarResult();
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

        if ($count > 0) {
            $this->assertArrayPropertyExists('data', $cc_ds);
        }
    }

}