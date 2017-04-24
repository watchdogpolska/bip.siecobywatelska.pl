<?php

namespace Sowp\ApiBundle\Controller;

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
            $this->get('jms_serializer')->serialize($collection, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/")
     * @Method("GET")
     */
    public function listAction()
    {

    }

    /**
     * @Route("/add", name="api_collection_add")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
         $response = new \StdClass;

        try {
//            $collection = $this
//                ->container
//                ->get('jms_serializer')
//                ->deserialize($request->getContent(), Collection::class, 'json');
//
//            $this
//                ->getDoctrine()
//                ->getEntityManager()
//                ->persist($collection);
//
//            $this
//                ->getDoctrine()
//                ->getEntityManager()
//                ->flush();

            $response->error = false;
            $response->success = true;
            $response->msg = "OK";
            $response->object = $request->getContent();

            return new JsonResponse($response,201);

        } catch (\Exception $e) {

            $response->error = true;
            $response->success = false;
            $response->msg =  $e->getMessage();
            return new JsonResponse($response,501);
        }
    }

    /**
     * @Route("/edit/{id}")
     * @Method("PUT")
     */
    public function editAction()
    {

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

    private function getSerialaizer()
    {
        return $this->get('serializer');
    }

    private function getApiHelper()
    {
        return $this->get('api_helper');
    }
}