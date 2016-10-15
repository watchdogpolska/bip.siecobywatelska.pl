<?php
/**
 * Created by PhpStorm.
 * User: andrzej
 * Date: 11.10.16
 * Time: 17:31
 */

namespace AppBundle\Dashboard;

class DashboardManager
{
    /**
     * @var DashboardProvider[]
     */
    private $providers = [];

    public function addElementsProvider(DashboardProvider $provider){
        $this->providers[] = $provider;
    }

    public function getElements(){
        $elements = [];
        foreach ($this->providers as $provider){
            $elements = array_merge($elements, $provider->getElements());
        }

        usort($elements, function(DashboardElement $a, DashboardElement $b) {
            return strcmp($a->getName(), $b->getName());
        });

        return $elements;
    }
}