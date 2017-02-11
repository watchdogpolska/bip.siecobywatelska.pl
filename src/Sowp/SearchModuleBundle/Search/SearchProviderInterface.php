<?php

namespace Sowp\SearchModuleBundle\Search;

interface SearchProviderInterface
{
    public function search($query, $numResMulti = 3);
    public function getTypeName();
    public function getResultsMulti();
    public function getResultsSingle();
    public function getQbSingle();
    public function getQbMulti();
    //public function setTypeName($name);
}