<?php

namespace Sowp\NewsModuleBundle\SearchProvider;

use Sowp\SearchModuleBundle\Search\SearchTemplateRegisterInterface;

class SearchTemplateProvider implements SearchTemplateRegisterInterface
{
    protected $template_single = 'NewsModuleBundle:search:single_res.html.twig';
    protected $template_multi = 'NewsModuleBundle:search:multi_res.html.twig';
    protected $index = 'news';

    public function getIndex()
    {
        return $this->index;
    }

    public function getTemplateMulti()
    {
        return $this->template_multi;
    }

    public function getTemplateSingle()
    {
        return $this->template_single;
    }

}