<?php

namespace Sowp\SearchModuleBundle\Search;

use Sowp\SearchModuleBundle\Search\SearchResultInterface;

class SearchManager
{
    /** @var array */
    private $providers = [];

    public function addProvider(SearchResultInterface $provider)
    {
        //each module may have only one search provider
        $this->providers[$provider->getTypeName()] = $provider;
    }

    public function getProviders()
    {
        return $this->providers;
    }

}