<?php

namespace Sowp\DashboardBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class DashboardElementPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('sowp.dashboard.manager')) {
            return;
        }

        $definition = $container->findDefinition('sowp.dashboard.manager');

        $taggedServices = $container->findTaggedServiceIds('sowp.dashboard.element_provider');
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addElementsProvider', array(new Reference($id)));
        }
    }
}