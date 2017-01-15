<?php

namespace Sowp\SearchModuleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SearchController
 * @package Sowp\SearchModuleBundle\Controller
 */
class SearchController extends Controller
{
    /**
     * @Route("/search", name="sowp_searchbundle_search")
     */
    public function searchAction(Request $request)
    {
        $q = $request->query->get("q", false);
        $sm = $this->get("sowp.bip.search_manager");
        $results = [];

        if (!$q || empty($q)) {
            throw new NotFoundHttpException();
        }

        foreach ($sm->getProviders() as $provider) {
            $results[] = $provider->getResultObject();
        }
    }
}