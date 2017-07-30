<?php
namespace Sowp\NewsModuleBundle\Tests\Controller;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\BrowserKit\Client;

class NewsControllerTest extends ApiTestCase
{
    /** @var  string|null $host */
    protected $host;

    /** @var Router $router */
    protected $router;

    public function setUp()
    {
        parent::setUp();

        $this->host = \rtrim($this->container->getParameter('php_server_name'), '/');
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
        $link = $this
            ->router
            ->generate('sowp_newsmodule_news_index');

        $response = $this->client->get($this->host.$link);
        $body = $response->getBody()->getContents();

        $this->assertEquals(200, $response->getStatusCode(), "Response code should be 200");

        $this->assertTrue(
            $this->apiStringContains('News list', $body),
            "NewsController::indexAction do not contain seeked text from template"
        );
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testNewActionAccomplish()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->router->generate('sowp_newsmodule_news_new'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Response code shaould be 200 ");

        $form = $crawler->selectButton("Add")->form();
        $values = $form->getValues();

        foreach ($values as $key => &$value) {
            switch ($key) {
                case 'news[title]':
                    $value = 'Title Test ' . \mt_rand();
                    break;
                case 'news[content]':
                    $value = 'Content';
                    break;
                default:
                    break;
            }
        }

        $form->setValues($values);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $linkNewArticle = $client->getResponse()->headers->get('Location');


        $client->request('GET', $linkNewArticle);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testEditActionAccomplish()
    {
        $n = $this->createNews();
        $client = static::createClient();
        $editLink = $this->router->generate('sowp_newsmodule_news_edit', [
            'slug' => $n->getSlug()
        ]);

        $client->request('GET', $editLink);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Response code shaould be 200 ");

        $form = $client->getCrawler()->selectButton("Edit")->form();
        $values = $form->getValues();

        foreach ($values as $key => &$value) {
            $val = \mt_rand();
            switch ($key) {
                case 'news[title]':
                    $value .= 'Edited ' . $val;
                    break;
                case 'news[content]':
                    $value .= 'Edited ' . $val;
                    break;
                default:
                    break;
            }
        }

        $form->setValues($values);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->request('GET', $client->getResponse()->headers->get('Location'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $clone = clone $client;
        $this->batchRevisionsListActionTest($clone);
        $this->batchDeleteActionTest($client);
    }

    private function batchDeleteActionTest(Client $c)
    {
        $crawler = $c->getCrawler();
        $form = $crawler->selectButton("Delete")->form();
        $c->submit($form);
        $this->assertEquals(302, $c->getResponse()->getStatusCode());
        $c->request('GET', $c->getResponse()->headers->get('Location'));
        $this->assertEquals(200, $c->getResponse()->getStatusCode());
    }

    private function batchRevisionsListActionTest(Client $c)
    {
        $crawler = $c->getCrawler();
        $link = $crawler->selectLink("Historia Zmian")->link();
        $crawler = $c->click($link);

        $this->assertEquals(200, $c->getResponse()->getStatusCode(), "Status code should be 200");
        $this->assertTrue(
            $this->apiStringContains("revisions", $crawler->html())
        );
    }
}
