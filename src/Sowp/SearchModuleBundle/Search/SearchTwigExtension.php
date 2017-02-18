<?php

namespace Sowp\SearchModuleBundle\Search;

use Sowp\SearchModuleBundle\Search\SearchResultInterface;

class SearchTwigExtension extends \Twig_Extension
{
    private $twig;

    public function __construct(\Twig_Environment $te)
    {
        $this->twig = $te;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'render_search_entry_multi',
                [
                    $this,
                    'renderSearchProviderMulti'
                ]
            ),
            new \Twig_SimpleFilter(
                'render_search_entry_single',
                [
                    $this,
                    'renderSearchProviderSingle'
                ]
            )
        ];
    }

    public function renderSearchProviderMulti($entity)
    {
        //$name = $this->getTemplateFromEntity($entity);

        if (!is_object($entity)) {
            throw new \LogicException("Template name from \$entity expected");
        }

        $name = $this->getTemplateFromEntity($entity);
        $name .= '_multi.html.twig';

        return $this->twig->render(
            "SearchModuleBundle:ProviderTemplates:$name",
            [
                'entity' => $entity
            ]
        );
    }

    public function renderSearchProviderSingle($entity)
    {
//        $this->checkIfEntity($entity);
    }

    private function checkIfEntity($e)
    {
        if (!is_object($e)) {
            throw new \LogicException("Entity expected.");
        }
    }

    private function getTemplateFromEntity($e)
    {
        $this->checkIfEntity($e);

        $name = explode('\\', \get_class($e));
        $name = strtolower(array_pop($name));

        if (!$name) {
            throw new \Exception("Name from \$entity expected");
        }

        return $name;
    }

    private function getTemplateFromEntityRegister()
    {
        /**
         * @TODO:
         *  v1
         *      ComplierPass collects tags
         *      that holds classes tht in to string
         *      returns template path...???
         */
    }
}