<?php

namespace Sowp\SearchModuleBundle\Search;

interface SearchResultInterface
{
    public function getHeaderName();
    public function getTypeName();
    public function getTemplateName();
    public function getResult();
}