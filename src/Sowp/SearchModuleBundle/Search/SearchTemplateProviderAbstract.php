<?php

namespace Sowp\SearchModuleBundle\Search;

abstract class SearchTemplateProviderAbstract implements SearchTemplateRegisterInterface
{
    private $template_single;
    private $template_multi;
    private $index;

    final public function __construct()
    {
        if (!\property_exists(__CLASS__, 'template_single') ||
            !\property_exists(__CLASS__, 'template_multi') ||
            !\property_exists(__CLASS__, 'index')) {
            throw new \LogicException("SearchTemplateProvider must have \$template_single,\$template_multi and \$index properties");
        }
    }

    final public function getIndex()
    {
        return $this->index;
    }

    final public function getTemplateMulti()
    {
        return $this->template_multi;
    }

    final public function getTemplateSingle()
    {
        return $this->template_single;
    }
}