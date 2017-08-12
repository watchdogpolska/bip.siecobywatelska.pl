<?php

namespace Sowp\ApiBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sowp\ApiBundle\Traits\ControllerTait;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\ArticleBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package Sowp\ApiBundle\Controller
 * @Route("/article")
 */
class ArticleController extends Controller
{
    use ControllerTait;

    /**
     * @Route("/{id}", name="api_article_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $art = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if (!$art) {
            $this->getApiHelper()->createErrorResponse(Response::HTTP_NOT_FOUND,
                'Not Found', $this->commonLinks());
        }

        $links = \array_merge($this->commonLinks(),[
            'self' => $this->get('router')->generate('api_article_show', ['id' => $id], Router::ABSOLUTE_URL)
        ]);

//        return $this->getApiHelper()->createApiResponse(Response::HTTP_OK, $art, $links);
        return new JsonResponse($this->getApiHelper()->createApiResponse(Response::HTTP_OK, $art, $links));
    }

    /**
     * @Route("/", name="api_article_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Article::class);
        $pagerAdapter = new DoctrineORMAdapter($repo->findAllQueryBuilder(), false);
        $col = new Pagerfanta($pagerAdapter);
        $articles = [];

        $col->setMaxPerPage($request->query->get('per_page', 10));
        $col->setCurrentPage($request->query->get('page', 1));

        foreach ($col->getCurrentPageResults() as $article) {
            $articles[] = $article;
        }

        $links = $this->getApiHelper()->generateNavLinks($col,'page', 'api_article_list');
        $links = \array_merge($links, $this->commonLinks());

        return new JsonResponse($this->getApiHelper()->createApiResponse(Response::HTTP_OK, $articles, $links));
//        return $this->getApiHelper()->createApiResponse(Response::HTTP_OK, $articles, $links);
    }

}