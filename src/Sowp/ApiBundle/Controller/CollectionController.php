<?php

namespace Sowp\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
    public function showAction()
    {

    }

    /**
     * @Route("/")
     * @Method("GET")
     */
    public function listAction()
    {

    }

    /**
     * @Route("/add")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $this
            ->get('jms_serializer')
            ->deserialize($request->getContent(),'array');
        return new JsonResponse([1,2,3], 201);
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

}