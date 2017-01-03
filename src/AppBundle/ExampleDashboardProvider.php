<?php

namespace AppBundle;

use AppBundle\Dashboard\DashboardElement;
use AppBundle\Dashboard\DashboardProvider;

//app.example_provider:
//        class: AppBundle\ExampleDashboardProvider
//        tags:
//          - { name: app.dashboard.element_provider }

class ExampleDashboardProvider implements DashboardProvider
{
    public function getElements()
    {
        return array(
            new DashboardElement('CAT', DashboardElement::TYPE_FONT_AWESOME, 'eye', 'http://google.pl'),
        );
    }
}
