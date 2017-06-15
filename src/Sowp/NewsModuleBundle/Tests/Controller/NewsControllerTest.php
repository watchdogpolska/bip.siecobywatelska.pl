<?php
namespace Sowp\NewsModuleBundle\Tests\Controller;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class NewsControllerTest extends ApiTestCase
{
    /** @var  string|null $host */
    protected $host;

    /** @var Router $router */
    protected $router;

    public function setUp()
    {
        parent::setUp();

        //exported enviroment var
        //$ export PHP_SERVER_NAME="http://your-server-name.com/"
        $this->host = \rtrim(\getenv('PHP_SERVER_NAME'), '/');
        $this->router = $this->container->get('router');
    }

    protected function tearDown()
    {
        $this->trashCollect(News::class);
    }

    public function testShowAction()
    {
        $n = $this->createNews();
        $title = $n->getTitle();
        $link = $this
            ->router
            ->generate('sowp_newsmodule_news_show', ['slug' => $n->getSlug()]);

        $response = $this->client->get($this->host.$link);

        $this->assertEquals(200, $response->getStatusCode(), "Response code should be 200");

        $this->assertTrue(
            $this->apiStringContains($title, $response->getBody()->getContents()),
            "NewsController::showAction do not contain seeked text from entity"
        );
    }

    public function testListAction()
    {
        $n1 = $this->createNews();
        $n2 = $this->createNews();
        $link = $this
            ->router
            ->generate('sowp_newsmodule_news_index');

        $response = $this->client->get($this->host.$link);
        $body = $response->getBody()->getContents();

        $this->assertEquals(200, $response->getStatusCode(), "Response code should be 200");
        $this->assertTrue(
            $this->apiStringContains($n1->getTitle(), $body),
            "NewsController::listAction do not contain seeked text from entity"
        );
        $this->assertTrue(
            $this->apiStringContains($n2->getTitle(), $body),
            "NewsController::listAction do not contain seeked text from entity"
        );
    }

    public function testNewActionExists()
    {
        $link = $this->router->generate('sowp_newsmodule_news_new');
        $response = $this->client->get($this->host.$link);

        $this->assertEquals(200, $response->getStatusCode(), "Response status code should be 200");

        $this->assertTrue(
            $this->apiStringContains("Add message", $response->getBody()->getContents()),
            "NewsController::newAction do not contain seeked text from entity"
        );
    }

    public function testEditActionExists()
    {
        $n = $this->createNews();
        $slug = $n->getSlug();
        $title = $n->getTitle();
        $link = $this->router->generate('sowp_newsmodule_news_edit', ['slug' => $slug]);
        $response = $this->client->get($this->host.$link);
        $body = $response->getBody()->getContents();

        $this->assertEquals(200, $response->getStatusCode(), "Response status code should be 200");
        $this->assertTrue(
            $this->apiStringContains("Message edit", $body),
            "NewsController::editAction do not contain seeked text from template"
        );

        $this->assertTrue(
            $this->apiStringContains($title, $body),
            "NewsController::editAction do not contain seeked text from entity"
        );
    }

    public function testNewActionAccomplish()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $link = $this->router->generate('sowp_newsmodule_news_new'));
        $form = $crawler->selectButton("Add")->form();
//        var_dump($form->all());
    }

    public function testEditActionAccomplish()
    {

    }
}
