<?php

namespace Sowp\SearchModuleBundle\Search;

class SearchManager
{
    /** @var array */
    private $providers = [];

    public function addProvider(SearchProviderInterface $provider)
    {
        //each module may have only one search provider
        $this->providers[$provider->getTypeName()] = $provider;
    }

    public function getProviders()
    {
        return $this->providers;
    }
}
