<?php

namespace Sowp\DashboardBundle\Dashboard;

interface DashboardProvider
{
    /**
     * @return DashboardElement[]
     */
    public function getElements();
}