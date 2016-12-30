<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class DashboardElementPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('app.dashboard.manager')) {
            return;
        }

        $definition = $container->findDefinition('app.dashboard.manager');

        $taggedServices = $container->findTaggedServiceIds('app.dashboard.element_provider');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addElementsProvider', array(new Reference($id)));
        }
    }
}