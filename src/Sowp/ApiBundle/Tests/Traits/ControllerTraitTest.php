<?php
namespace Sowp\ApiBundle\Tests\Traits;

use Sowp\ApiBundle\Traits\ControllerTait;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ControllerTraitTest extends TestCase
{
    public function testGetSerializer()
    {
        $mock = $this->getMockForTrait(ControllerTait::class);
//        $mock
//            ->expects($this->any())
//            ->method('getSerializer')
//            ->will($this->re)
    }
}