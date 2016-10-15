<?php

namespace AppBundle\Dashboard;


interface DashboardProvider
{
    /**
     * @return DashboardElement[]
     */
    public function getElements();
}