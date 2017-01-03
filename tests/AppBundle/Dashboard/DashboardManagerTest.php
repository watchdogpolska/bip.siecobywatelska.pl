<?php

namespace Tests\AppBundle\Dashboard;

use AppBundle\Dashboard\DashboardElement;
use AppBundle\Dashboard\DashboardManager;

class DashboardManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetElements()
    {
        $manager = new DashboardManager();

        $provider1 = $this->getProviderMock(array('AA', 'AB', 'CC'));
        $provider2 = $this->getProviderMock(array('AA', 'BB'));

        $manager->addElementsProvider($provider1);
        $manager->addElementsProvider($provider2);

        $elements = $manager->getElements();

        $this->assertCount(5, $elements);

        $this->assertSame(array_map(function (DashboardElement $element) {
            return $element->getName();
        }, $elements), array('AA', 'AA', 'AB', 'BB', 'CC'));
    }

    private function getManagerMock($methods = array())
    {
        return $this->getMockBuilder('AppBundle\Dashboard\DashboardManager')
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getProviderMock($names = array())
    {
        $mock = $this->getMockBuilder('\AppBundle\Dashboard\DashboardProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getElements')->willReturn(array_map(array($this, 'getElementMock'), $names));

        return $mock;
    }

    private function getElementMock($name)
    {
        $mock = $this->getMockBuilder('\AppBundle\Dashboard\DashboardElement')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getName')->willReturn($name);

        return $mock;
    }
}
