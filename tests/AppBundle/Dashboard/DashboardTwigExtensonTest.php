<?php
/**
 * Created by PhpStorm.
 * User: andrzej
 * Date: 11.10.16
 * Time: 23:13.
 */

namespace Tests\AppBundle\Dashboard;

use AppBundle\Dashboard\DashboardTwigExtension;

class DashboardTwigExtensonTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Twig_Environment')) {
            $this->markTestSkipped('Twig is not available');
        }
    }

    public function testRenderDashboard()
    {
        $helper = $this->getRendererMock(array('render'));
        $helper->expects($this->once())
            ->method('render')
            ->with('grid')
            ->will($this->returnValue('<p>cat</p>'))
        ;
        $this->assertEquals('<p>cat</p>', $this->getTemplate('{{ sowp_dashboard_render() }}', $helper)->render(array()));
    }

    private function getRendererMock(array $methods)
    {
        return $this->getMockBuilder('AppBundle\Dashboard\DashboardRenderer')
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock()
            ;
    }

    private function getTemplate($template, $renderer)
    {
        $loader = new \Twig_Loader_Array(array('index' => $template));
        $twig = new \Twig_Environment($loader, array('debug' => true, 'cache' => false));
        $twig->addExtension(new DashboardTwigExtension($renderer));

        return $twig->loadTemplate('index');
    }
}
