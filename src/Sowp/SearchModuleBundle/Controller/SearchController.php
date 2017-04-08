<?php

namespace Sowp\SearchModuleBundle\Controller;

use Sowp\SearchModuleBundle\SearchModuleBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchController extends Controller
{
    const NEWS_PER_PAGE = 5;

    /**
     * @Route("/search", name="sowp_searchbundle_search")
     */
    public function searchAction(Request $request)
    {
        $q = $request->query->get('q', false);
        $module = $request->query->get('mod', false);
        $p = $request->query->get('page', 1);
        $results = [];

        if (!$q || empty($q)) {
            throw new NotFoundHttpException();
        }

        $sm = $this->get('sowp.bip.search_manager');

        /**
         * @var SearchModuleBundle\Search\SearchResultInterface
         */
        foreach ($sm->getProviders() as $provider) {
            //initiate provider
            $provider->search($q);
            $results[$provider->getTypeName()] = $provider->getResults();

            if ($module === \strtolower($provider->getTypeName())) {
                // here we know that single mode search was requested
                // we want query builder for pagerfanta
                // we can use existing because we already searched =]
                $pagerAdapter = new DoctrineORMAdapter($provider->getQb(), false);
                $itemsSingle = new Pagerfanta($pagerAdapter);
                $itemsSingle->setMaxPerPage(self::NEWS_PER_PAGE);
                $itemsSingle->setCurrentPage($p);
            }
        }

        return $this->render('SearchModuleBundle::search.html.twig', [
            'query' => $q,
            'results' => $results,
            'items_single' => isset($itemsSingle) ? $itemsSingle : false,
        ]);
    }
}
