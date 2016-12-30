<?php
/**
 * Created by PhpStorm.
 * User: andrzej
 * Date: 11.10.16
 * Time: 18:26
 */

namespace AppBundle\Dashboard;


class DashboardRenderer
{
    public function __construct(DashboardManager $manager, \Twig_Environment $templating)
    {
        $this->manager = $manager;
        $this->templating = $templating;
    }

    public function render($name = 'grid')
    {
        $elements = $this->manager->getElements();
        return $this->templating->render("dashboard/_{$name}.html.twig", compact('elements'));
    }
}