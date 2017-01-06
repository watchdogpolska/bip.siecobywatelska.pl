<?php
/**
 * Created by PhpStorm.
 * User: andrzej
 * Date: 30.12.16
 * Time: 03:43.
 */

namespace Sowp\MenuBundle\Dashboard;

use Sowp\DashboardBundle\Dashboard\DashboardElement;
use Sowp\DashboardBundle\Dashboard\DashboardProvider;
use Symfony\Component\Routing\Router;

class MenuDashboardProvider implements DashboardProvider
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return DashboardElement[]
     */
    public function getElements()
    {
        return array(
            new DashboardElement(
                'Menu',
                DashboardElement::TYPE_FONT_AWESOME,
                'bars',
                $this->router->generate('admin_menuitem_index')
            ),
        );
    }
}
