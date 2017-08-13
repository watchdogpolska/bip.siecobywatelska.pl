<?php

namespace Sowp\SearchModuleBundle\Tests;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Component\DomCrawler\Link;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SearchControllerTest
 * @package Sowp\SearchModuleBundle\Tests
 * @scenario:
 *   - we generate seed in constant
 *   - we generate 2 unique phrases seeded with constant seed
 *     (so search phrase consists of constant seed and unique string)
 *   - we create 100 article and 100 news
 *   - we modify 10 of each that each title contains one of two search phrases
 *     and content mixed 2 phrases
 *   - we request search controller with constant seed phrase
 *   - asserting responsecode
 *   - asserting html contains
 *   - search for "more..." links
 *   - follow one of that links (gained from actual HTML)
 *   - assert response code
 *   - assert html contains phrase
 */
class SearchControllerTest extends ApiTestCase
{
    const SEARCH_MOCK = ' voluptatis ';

    /** @var  string|null $host */
    protected $host;

    /** @var Router $router */
    protected $router;

    public function setUp()
    {
        parent::setUp();

        $this->host = \rtrim($this->container->getParameter('php_server_name'), '/');
        $this->router = $this->container->get('router');

        //load Fixtures
        $this->container->get('app_bundle.fixtures_loader')->addAll();
        $this->container->get('app_bundle.fixtures_loader')->loadAllFromQueue();

        $articleRepo = $this->em->getRepository(Article::class);
        $newsRepo = $this->em->getRepository(News::class);

        // Setup proper search results Article
        // Mock Search phrase into content and title
        $articles = $articleRepo->findAll();
        $news = $newsRepo->findAll();

        for ($x = 0; $x < 10; $x++) {
            $searchPhraseMock1 = self::SEARCH_MOCK . \uniqid('', true);
            $searchPhraseMock2 = self::SEARCH_MOCK . \uniqid('', true);

            /**
             * @var $message News
             */
            $message = \array_pop($news);

            /**
             * @var $article Article
             */
            $article = \array_pop($articles);

            $message->setTitle($searchPhraseMock1);
            $message->setContent($searchPhraseMock1 . $searchPhraseMock2 . $message->getContent());
            $this->em->persist($message);

            $article->setTitle($searchPhraseMock2);
            $article->setContent($searchPhraseMock1 . $searchPhraseMock2 . $article->getContent());
            $this->em->persist($article);

        }

        $this->em->flush();
    }

    /**
     * using PHPUnit/Symfony client because
     * of handy crawler
     */
    public function testSearchAction()
    {
        $client = static::createAuthClient();
        $crawler = $client->request(
            'GET',
            $this->router->generate('sowp_searchbundle_search'),
            [
                'q' => self::SEARCH_MOCK
            ]
        );

        $headers = $crawler->filter('h2.search-result');
        $this->assertGreaterThan(4, $headers->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Response code should be 200");
        $this->assertTrue(
            $this->apiStringContains(\trim(self::SEARCH_MOCK), $client->getResponse()->getContent())
        );

        $this->assertTrue(
            $this->apiStringContains('search_multi_results', $client->getResponse()->getContent())
        );

        /**
         * @var $links \DOMElement[]
         */
        $links = $crawler->filter("a");

        foreach ($links as $link) {
            $href = $link->nodeValue;

            if (\preg_match('/^Zobacz wi/', $href)) {

                /**
                 * @var $attrs \DOMNamedNodeMap
                 */
                $attrs = $link->attributes;
                $searchQuery = $attrs->item(1)->nodeValue;
                break;
            }
        }

        // links contains spaces - Client cant handle that (?or can?)
        // so for this moment this is workaround
        $searchQuery = str_replace(' ', '', $searchQuery);

        $secondLink = $searchQuery;
        $client->request(
            Request::METHOD_GET,
            $secondLink
        );


        $this->assertEquals(Response::HTTP_OK, $this->httpCode($client));
        $this->assertTrue(
            $this->apiStringContains(
                'search_single_results',
                $client->getResponse()->getContent()
            )
        );
    }
}