<?php

namespace Sowp\DashboardBundle\Dashboard;

class DashboardTwigExtension extends \Twig_Extension
{

    /** @var DashboardRenderer  */
    private $renderer;

    public function __construct(DashboardRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'sowp_dashboard_render',
                array(
                    $this,
                    'render'
                ),
                array('is_safe' => array('html'))
            )
        );
    }

    public function getName()
    {
        return "dashboard_extension";
    }

    public function render($name = 'grid')
    {
        return $this->renderer->render($name);
    }
}