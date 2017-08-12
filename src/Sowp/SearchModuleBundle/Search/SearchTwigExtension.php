<?php

namespace Sowp\SearchModuleBundle\Search;

class SearchTwigExtension extends \Twig_Extension
{
    const MODE_MULTI = 22;
    const MODE_SINGLE = 32;

    private $twig;
    private $templateRegister;

    public function __construct(\Twig_Environment $te, SearchTemplateRegister $tr)
    {
        $this->twig = $te;
        $this->templateRegister = $tr;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'render_search_entry_multi',
                [
                    $this,
                    'renderSearchProviderMulti',
                ],
                [
                    'is_safe' => ['html']
                ]
            ),
            new \Twig_SimpleFilter(
                'render_search_entry_single',
                [
                    $this,
                    'renderSearchProviderSingle',
                ],
                [
                    'is_safe' => ['html']
                ]
            ),
        ];
    }

    public function renderSearchProviderMulti($entity)
    {
        $index = $this->getTemplateFromEntity($entity);

        if (($template = $this->getTemplateFromRegister($index, self::MODE_MULTI)) === false) {
            $template = "SearchModuleBundle:ProviderTemplates:{$index}_multi.html.twig";
        }

        return $this->twig->render(
            $template,
            [
                'entity' => $entity,
            ]
        );
    }

    public function renderSearchProviderSingle($entity)
    {
        $index = $this->getTemplateFromEntity($entity);

        if (($template = $this->getTemplateFromRegister($index, self::MODE_SINGLE)) === false) {
            $template = "SearchModuleBundle:ProviderTemplates:{$index}_single.html.twig";
        }

        return $this->twig->render(
            $template,
            [
                'entity' => $entity,
            ]
        );
    }

    private function checkIfEntity($e)
    {
        if (!is_object($e)) {
            throw new \LogicException('Entity expected.');
        }
    }

    private function getTemplateFromEntity($e)
    {
        $this->checkIfEntity($e);

        $name = explode('\\', \get_class($e));
        $name = strtolower(array_pop($name));

        if (!$name) {
            throw new \Exception('Name from $entity expected');
        }

        return $name;
    }

    private function getTemplateFromRegister($index, $mode)
    {
        if (!$this->templateRegister->hasElement($index)) {
            return false;
        }

        $templateProvider = $this->templateRegister->getElement($index);

        if ($mode === self::MODE_MULTI) {
            return $templateProvider->getTemplateMulti();
        } elseif ($mode === self::MODE_SINGLE) {
            return $templateProvider->getTemplateSingle();
        }
    }
}
