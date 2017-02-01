<?php

namespace Sowp\SearchModuleBundle\Search;

interface SearchResultInterface
{
    public function search($query);
    public function getResults();
    public function getTypeName();
    public function setTypeName($name);
}