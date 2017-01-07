<?php

namespace Sowp\SearchModuleBundle\Search;

use Sowp\SearchModuleBundle\Search\SearchInterface;

class SearchManager
{
    /** @var array */
    private $providers = [];

    public function addProvider(SearchInterface $provider)
    {
        //each module may have only one search provider
        $this->providers[$provider->getModule()] = $provider;
    }

    public function searchAll()
    {

    }
}