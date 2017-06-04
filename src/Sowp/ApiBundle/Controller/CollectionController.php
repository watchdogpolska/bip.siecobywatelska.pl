<?php

namespace Sowp\ApiBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sowp\ApiBundle\Response\Link;
use Sowp\ApiBundle\Traits\ControllerTait;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\CollectionBundle\Form\addCollectionForm;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\NewsModuleBundle\Form\NewsType;
use Sowp\NewsModuleBundle\Form\TestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class ControllerController
 * @package Sowp\ApiBundle\Controller
 * @Route("/collections")
 */
class CollectionController extends Controller
{
    use ControllerTait;

    /**
     * @Route("/{id}", name="api_collections_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $col = $this->getDoctrine()->getRepository(Collection::class)->find($id);
        $apiHelper = $this->get('api_helper');

        if (!$col) {
            return $apiHelper->createErrorResponse(404, "Not Found", $this->commonLinks());
        }

        $links = \array_merge($this->commonLinks(),[
            'self' => $this->get('router')->generate('api_collections_show', ['id' => $id], Router::ABSOLUTE_URL)
        ]);

        return $apiHelper->createApiResponse(Response::HTTP_OK, $col, $links);
    }

    /**
     * @Route("/", name="api_collections_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {

        $repo = $this->getDoctrine()->getManager()->getRepository(Collection::class);
        $pagerAdapter = new DoctrineORMAdapter($repo->getQueryBuilderAll(), false);
        $col = new Pagerfanta($pagerAdapter);
        $collections = [];

        $col->setMaxPerPage($request->query->get('per_page', 10));
        $col->setCurrentPage($request->query->get('page', 1));

        foreach ($col->getCurrentPageResults() as $collection) {
            $collections[] = $collection;
        }

        $links = $this->getApiHelper()->generateNavLinks($col,'page', 'api_collections_list');
        $links = \array_merge($links, $this->commonLinks());

        return $this->getApiHelper()->createApiResponse(Response::HTTP_OK, $collections, $links);
    }
}