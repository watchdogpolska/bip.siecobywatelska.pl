<?php


namespace Sowp\ArticleBundle\Tests\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Sowp\ArticleBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{

	public function setUp()
	{
		self::bootKernel();
	}

	public static function getContainer() {
		return self::$kernel->getContainer();
	}

	/**
	 * @return EntityManager
	 */
	public static function getEntityManager() {
	    return self::getContainer()->get('doctrine')->getManager();
    }

    public static function url($name, $parameters = array(), $referenceType = Router::ABSOLUTE_PATH) {
	    return self::getContainer()->get('router')->generate($name, $parameters, $referenceType);
    }

    public function login($login = null, $password = null) {
	    $client = self::createClient();

	    $this->createUser('user', 'password');

	    $crawler = $client->request('GET', '/login');
	    $form = $crawler->filter('form')->form();
	    $form['_username'] = 'user';
		$form['_password'] = 'password';
	    $client->submit($form);

		return $client;
    }

    public function testShowAction()
    {
	    /**
	     * @var $instance Article
	     */
	    $instance = $this->createArticle();
	    $url = $this->url('admin_article_show', ['id' => $instance->getId()]);

        $client = $this->login();
		$crawler = $client->request('GET', $url);

	    $linkNewArticle = $client->getResponse()->headers->get('Location');

	    $response = $client->getResponse();
	    $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains($instance->getTitle(), $response->getContent());
    }

    public function testListAction()
    {
	    $instance = $this->createArticle();
	    $url = $this->url('admin_article_index');

	    $client = $this->login();
	    $crawler = $client->request('GET', $url);

	    $response = $client->getResponse();
	    $this->assertEquals(200, $response->getStatusCode());
	    $this->assertContains($instance->getTitle(), $response->getContent());
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testNewActionAccomplish()
    {
        $client = $this->login();
	    $url = $this->url('admin_article_new');
        $crawler = $client->request('GET', $url);

	    $response = $client->getResponse();
	    $this->assertEquals(200, $response->getStatusCode());

        $form = $crawler->filter('form[name="article"]')->form();
        $form['article[title]'] = 'New Title';
        $form['article[content]'] = 'Content';
	    $form['article[editNote]'] = 'note';
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $linkNewArticle = $client->getResponse()->headers->get('Location');
        $client->request('GET', $linkNewArticle);

	    $this->assertEquals(200, $client->getResponse()->getStatusCode());
	    $this->assertContains('New Title', $client->getResponse()->getContent());
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testEditActionAccomplish()
    {
	    $instance = $this->createArticle();
        $url = $this->url('admin_article_edit', ['id' => $instance->getId()]);

	    $client  = $this->login();
	    $crawler = $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $client->getCrawler()->selectButton("Edit")->form();
        $form['article[title]'] = 'Title Test ' . \mt_rand();

        $form['article[content]'] = 'Content ';
		$form['article[editNote]'] = 'note';

        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->request('GET', $client->getResponse()->headers->get('Location'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRevisionList()
    {
	    $instance = $this->createArticle();
	    $url_edit = $this->url('admin_article_edit', ['id' => $instance->getId()]);
	    $url_show = $this->url('admin_article_show', ['id' => $instance->getId()]);

	    $client  = $this->login();
	    $crawler = $client->request('GET', $url_edit);

	    $form = $client->getCrawler()->selectButton("Edit")->form();
	    $form['article[title]'] = 'Title 1';
	    $form['article[content]'] = 'Content';
	    $form['article[editNote]'] = 'Note 1';
	    $client->submit($form);
	    $form['article[title]'] = 'Title 1';
	    $form['article[editNote]'] = 'Note 2';
	    $client->submit($form);

	    $crawler = $client->request('GET', $url_show);

		$descriptions = $crawler->filter('.list-group .list-group-item-text');
		$this->assertCount(3, $descriptions);
	    $this->assertContains("Note 2", $descriptions->eq(0)->text());
	    $this->assertContains("Note 1", $descriptions->eq(1)->text());
	    $this->assertContains("Create", $descriptions->eq(2)->text());

    }

	public function createUser($username = 'user', $password  = 'password', $email = null) {
		$em = $this->getEntityManager();

		$user = new User();
		$user->setUsername($username);
		$user->setPlainPassword($password);
		$user->setEmail($email == null ? $username . '@example.com' : $email);
		$user->setEnabled(true);
		$user->setRoles(array('ROLE_SUPER_ADMIN'));

		$em->persist($user);
		$em->flush();

		return $user;
	}

	public function createArticle() {
		$article = new Article();
		$article->setTitle("Title");
		$article->setContent("Content");
		$article->setEditNote("Create");

		$em = $this->getEntityManager();
		$em->persist($article);
		$em->flush();

		return $article;
	}
}
