<?php

namespace Sowp\NewsModuleBundle\SearchProvider;

use Sowp\SearchModuleBundle\Search\SearchTemplateProviderAbstract;

class SearchTemplateProvider extends SearchTemplateProviderAbstract
{
    private $template_single = 'NewsModuleBundle:news:single_res.html.twig';
    private $template_multi = 'NewsModuleBundle:news:multi_res.html.twig';
    private $index = 'news';
}