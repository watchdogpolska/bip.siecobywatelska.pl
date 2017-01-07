<?php

namespace Sowp\SearchModuleBundle\Search;

interface SearchInterface
{
    public function searchMe($phrase);
    public function getModule();
}