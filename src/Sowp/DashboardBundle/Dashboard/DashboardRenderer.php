<?php

namespace Sowp\DashboardBundle\Dashboard;

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

        return $this->templating->render("SowpDashboardBundle:dashboard:_{$name}.html.twig", compact('elements'));
    }
}
