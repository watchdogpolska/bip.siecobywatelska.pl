<?php
namespace Sowp\ArticleBundle\Tests\Controller;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\ArticleBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DomCrawler\Link;

class ArticleControllerTest extends ApiTestCase
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
        $this->trashCollect(Article::class);
    }

    public function testShowAction()
    {
        $a = $this->createArticle();
        $title = $a->getTitle();
        $link = $this
            ->router
            ->generate('admin_article_show', ['id' => $a->getId()]);

        $response = $this->client->get($this->host.$link);

        $this->assertEquals(200, $response->getStatusCode(), "Response code should be 200");

        $this->assertTrue(
            $this->apiStringContains($title, $response->getBody()->getContents()),
            "NewsController::showAction do not contain seeked text from entity"
        );
    }

    public function testListAction()
    {
        $a1 = $this->createArticle();
        $a2 = $this->createArticle();
        $link = $this
            ->router
            ->generate('admin_article_index');

        $response = $this->client->get($this->host.$link);
        $body = $response->getBody()->getContents();

        $this->assertEquals(200, $response->getStatusCode(), "Response code should be 200");

        $this->assertTrue(
            $this->apiStringContains('List', $body),
            "NewsController::indexAction do not contain seeked text from template"
        );
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testNewActionAccomplish()
    {
        $client = $this->createAuthClient();
        $crawler = $client->request('GET', $this->router->generate('admin_article_new'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Response code shaould be 200 ");

        $form = $crawler->selectButton("Add")->form();
        $values = $form->getValues();

        foreach ($values as $key => &$value) {
            switch ($key) {
                case 'article[title]':
                    $value = 'Title Test ' . \mt_rand();
                    break;
                case 'article[content]':
                    $value = 'Content ';
                    break;
                case 'article[editNote]':
                    $value = 'note';
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
        $a = $this->createArticle();
        $client = $this->createAuthClient();
        $editLink = $this->router->generate('admin_article_edit', [
            'id' => $a->getId()
        ]);

        $client->request('GET', $editLink);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $client->getCrawler()->selectButton("Edit")->form();
        $values = $form->getValues();

        foreach ($values as $key => &$value) {
            switch ($key) {
                case 'article[title]':
                    $value = 'Title Test ' . \mt_rand();
                    break;
                case 'article[content]':
                    $value = 'Content ';
                    break;
                case 'article[editNote]':
                    $value = 'note';
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
        /**
         * TODO:
         * fix article redirect after delete action
         */
//        $this->batchDeleteActionTest($client);
    }
//
//    private function batchDeleteActionTest(Client $c)
//    {
//        $crawler = $c->getCrawler();
//        $form = $crawler->selectButton("Delete")->form();
//        $c->submit($form);
//        $this->assertEquals(302, $c->getResponse()->getStatusCode());
//        $c->request('GET', $c->getResponse()->headers->get('Location'));
//        $this->assertEquals(200, $c->getResponse()->getStatusCode());
//    }
//
    private function batchRevisionsListActionTest(Client $c)
    {
        $crawler = $c->getCrawler();

        $this->assertTrue(
            $this->apiStringContains("Revisions", $crawler->html())
        );

        $links = $crawler->filter("a.list-group-item")->links();

        $c->click($links[0]);
        $this->assertEquals(200, $c->getResponse()->getStatusCode());
    }
}
