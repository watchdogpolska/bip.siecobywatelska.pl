<?php

namespace Sowp\DashboardBundle;

use Sowp\DashboardBundle\DependencyInjection\Compiler\DashboardElementPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SowpDashboardBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DashboardElementPass());
    }
}
