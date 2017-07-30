<?php
namespace Sowp\CollectionBundle\Tests\Controller;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\BrowserKit\Client;

class CollectionControllerTest extends ApiTestCase
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
        $this->trashCollect(Collection::class);
    }

    public function testShowAction()
    {
        $c = $this->createCollection();
        $title = $c->getTitle();
        $link = $this
            ->router
            ->generate('admin_collections_show', ['slug' => $c->getSlug()]);

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
            ->generate('admin_collections_index');

        $response = $this->client->get($this->host.$link);
        $body = $response->getBody()->getContents();

        $this->assertEquals(200, $response->getStatusCode(), "Response code should be 200");

        $this->assertTrue(
            $this->apiStringContains('Collection list', $body),
            "CollectionController::indexAction do not contain seeked text from template"
        );
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testNewActionAccomplish()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->router->generate('admin_collections_add'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Response code shaould be 200 ");

        $form = $crawler->selectButton("Add")->form();
        $values = $form->getValues();

        foreach ($values as $key => &$value) {
            switch ($key) {
                case 'add_collection_form[title]':
                    $value = 'Title Test ' . \mt_rand();
                    break;
                case 'add_collection_form[parent]':
                    unset($values[$key]);
                    break;
                case 'add_collection_form[public]':
                    $value = 1;
                default:
                    break;
            }
        }

        $form->setValues($values);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $linkNewCol = $client->getResponse()->headers->get('Location');

        $client->request('GET', $linkNewCol);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testEditActionAccomplish()
    {
        $c = $this->createCollection();
        $client = static::createClient();
        $editLink = $this->router->generate('admin_collections_edit', [
            'slug' => $c->getSlug()
        ]);

        $client->request('GET', $editLink);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $client->getCrawler()->selectButton("Edit")->form();
        $values = $form->getValues();

        foreach ($values as $key => &$value) {
            $val = \mt_rand();
            switch ($key) {
                case 'add_collection_form[title]':
                    $value .= ' Edited ' . $val;
                    break;
                case 'add_collection_form[parent]':
                    unset($values[$key]);
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
}
