<?php

namespace Sowp\SearchModuleBundle\Search;

interface SearchTemplateRegisterInterface
{
    public function getIndex();

    public function getTemplateMulti();

    public function getTemplateSingle();
}
