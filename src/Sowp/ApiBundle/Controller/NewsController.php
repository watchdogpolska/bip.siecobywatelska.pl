<?php
namespace Sowp\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\NewsModuleBundle\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package Sowp\ApiBundle\Controller
 * @Route("/messages")
 */
class NewsController extends Controller
{
    /**
     * @Route("/", name="api_news_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {

        $repo = $this->getDoctrine()->getManager()->getRepository(News::class);
        $pagerAdapter = new DoctrineORMAdapter($repo->getQueryBuilderAll(), false);
        $col = new Pagerfanta($pagerAdapter);
        $news = [];

        $col->setMaxPerPage($request->query->get('per_page', 10));
        $col->setCurrentPage($request->query->get('page', 1));

        foreach ($col->getCurrentPageResults() as $entry) {
            $news[] = $entry;
        }
        $links = $this->getApiHelper()->generateNavLinks($col, 'page', 'api_news_list');
        $links = \array_merge($links, $this->commonLinks());

        return $this->getApiHelper()->createApiResponse(Response::HTTP_OK, $news, $links);
    }

    /**
     * @Route("/{id}", name="api_news_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $news = $this->getDoctrine()->getRepository(News::class)->find($id);

        if (!$news) {
            return $this->getApiHelper()->createErrorResponse(Response::HTTP_NOT_FOUND,
                'Not Found', $this->commonLinks());
        }

        $links = \array_merge($this->commonLinks(),[
            'self' => $this->get('router')->generate('api_news_show', ['id' => $id], Router::ABSOLUTE_URL)
        ]);

        return $apiHelper->createApiResponse(Response::HTTP_OK, $col, $links);
    }

    private function getSerializer()
    {
        return $this->get('serializer');
    }

    private function getApiHelper()
    {
        return $this->get('api_helper');
    }

    private function commonLinks()
    {
        return [
            'collection_index' => $this->get('router')->generate('api_collections_list'),
            'article_index' => $this->get('router')->generate('api_article_list'),
            'messages_index' => $this->get('router')->generate('api_news_list')
        ];
    }
}