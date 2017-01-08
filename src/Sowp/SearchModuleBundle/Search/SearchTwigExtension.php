<?php

namespace Sowp\SearchModuleBundle\Search;

use Sowp\SearchModuleBundle\Search\SearchResultInterface;

class SearchTwigExtension extends \Twig_Extension
{
    private $twig;

    public function __construct(\Twig_Environment $te)
    {
        $this->twig = $te;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'render_search_provider',
                [
                    $this,
                    'renderSearchProvider'
                ]
            )
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'object_title_search_provider',
                [
                    $this,
                    'titleSearchProvider'
                ]
            )
        ];
    }

    public function renderSearchProvider(SearchResultInterface $provider)
    {

    }

    public function titleSearchProvider(SearchResultInterface $provider)
    {

    }
}