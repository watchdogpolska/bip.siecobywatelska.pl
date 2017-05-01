<?php

namespace Sowp\ApiBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sowp\CollectionBundle\Entity\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ControllerController
 * @package Sowp\ApiBundle\Controller
 * @Route("/collections")
 */
class CollectionController extends Controller
{
    /**
     * @Route("/{id}")
     * @Method("GET")
     */
    public function showAction(Collection $collection)
    {
        return new Response(
            $this
                ->get('jms_serializer')
                ->serialize($collection, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {

        $repo = $this->getDoctrine()->getManager()->getRepository(Collection::class);
        $pagerAdapter = new DoctrineORMAdapter($repo->getQueryBuilderAll(), false);
        $col = new Pagerfanta($pagerAdapter);
        $result = [];

        $col->setMaxPerPage($request->query->get('per_page', 10));
        $col->setCurrentPage($request->query->get('page', 1));

        foreach ($col->getCurrentPageResults() as $collection) {
            $result[] = $collection;
        }

        return new Response(
            $this
                ->getSerializer()
                ->serialize($result, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/add", name="api_collection_add")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        try {
            $body = $request->getContent();
            $collection = $this->getSerializer()->deserialize($body, Collection::class, 'json');
            $this->get('doctrine')->getEntityManager()->persist($collection);
            $this->get('doctrine')->getEntityManager()->flush();

            return new Response(
                $this->getSerializer()->serialize($collection, 'json'),
                Response::HTTP_CREATED,
                ['content-type' => 'application/json']
            );
        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @Route("/edit/{id}")
     * @Method("PUT")
     */
    public function editAction(Collection $collection, Request $request)
    {
        $payload = $request->getContent();
    }

    /**
     * @Route("/patch/{id}")
     * @Method("PATCH")
     */
    public function patchAction()
    {

    }

    /**
     * @Route("/remove/{id}")
     * @Method("DELETE")
     */
    public function removeAction()
    {

    }

    private function getSerializer()
    {
        return $this->get('serializer');
    }

    private function getApiHelper()
    {
        return $this->get('api_helper');
    }
}