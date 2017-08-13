<?php

namespace Tests\AppBundle\Dashboard;

use Sowp\DashboardBundle\Dashboard\DashboardElement;
use Sowp\DashboardBundle\Dashboard\DashboardManager;
use Sowp\DashboardBundle\Dashboard\DashboardProvider;

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

    /**
     * @return DashboardManager
     */
    private function getManagerMock($methods = array())
    {
        return $this->getMockBuilder(DashboardManager::class)
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
    }

	/**
	 * @return DashboardProvider
	 */
    private function getProviderMock($names = array())
    {
        $mock = $this->getMockBuilder(DashboardProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getElements')->willReturn(array_map(array($this, 'getElementMock'), $names));

        return $mock;
    }

	/**
	 * @param $name
	 *
	 * @return DashboardElement
	 */
    private function getElementMock($name)
    {
        $mock = $this->getMockBuilder(DashboardElement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getName')->willReturn($name);

        return $mock;
    }
}
