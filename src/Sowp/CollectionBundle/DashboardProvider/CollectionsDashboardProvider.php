<?php
namespace  Sowp\CollectionBundle\DashboardProvider;

use Sowp\DashboardBundle\Dashboard\DashboardElement;
use Sowp\DashboardBundle\Dashboard\DashboardProvider;
use Symfony\Component\Routing\Router;

class CollectionsDashboardProvider implements DashboardProvider
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
                'Collections Index',
                DashboardElement::TYPE_FONT_AWESOME,
                'code',
                $this->router->generate('admin_collections_index')
            ),
        );
    }

    public function __construct(Router $router)
    {
        $this->router = $router;
    }
}
