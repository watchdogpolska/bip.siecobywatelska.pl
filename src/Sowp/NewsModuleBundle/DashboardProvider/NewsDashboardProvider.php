<?php

namespace Sowp\NewsModuleBundle\DashboardProvider;

//use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Sowp\DashboardBundle\Dashboard\DashboardElement;
use Sowp\DashboardBundle\Dashboard\DashboardProvider;
use Symfony\Component\Routing\Router;

class NewsDashboardProvider implements DashboardProvider
{
    private $router;

    /**
     * @return DashboardElement[]
     */
    public function getElements()
    {
        return array(
            //__construct($name, $type, $icon, $href)
            new DashboardElement(
                'News Index',
                DashboardElement::TYPE_FONT_AWESOME,
                'envelope',
                $this->router->generate('sowp_newsmodule_news_index')
            ),
        );
    }

    public function __construct(Router $router)
    {
        $this->router = $router;
    }
}
