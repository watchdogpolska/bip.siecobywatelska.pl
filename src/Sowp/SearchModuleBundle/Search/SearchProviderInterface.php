<?php

namespace Sowp\SearchModuleBundle\Search;

interface SearchProviderInterface
{
    public function search($query);

    public function getTypeName();

    public function getResults();

    public function getQb();
}
