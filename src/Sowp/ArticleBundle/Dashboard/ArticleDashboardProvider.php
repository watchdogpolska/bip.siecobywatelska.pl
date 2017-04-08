<?php
/**
 * Created by PhpStorm.
 * User: andrzej
 * Date: 30.12.16
 * Time: 03:43.
 */

namespace Sowp\ArticleBundle\Dashboard;

use Sowp\DashboardBundle\Dashboard\DashboardElement;
use Sowp\DashboardBundle\Dashboard\DashboardProvider;
use Symfony\Component\Routing\Router;

class ArticleDashboardProvider implements DashboardProvider
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
                'Article',
                DashboardElement::TYPE_FONT_AWESOME,
                'book',
                $this->router->generate('admin_article_index')
            ),
            new DashboardElement(
                'Bookmark',
                DashboardElement::TYPE_FONT_AWESOME,
                'bookmark',
                $this->router->generate('admin_collection_index')
            ),
        );
    }
}
