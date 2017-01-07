<?php

namespace Sowp\SearchModuleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SearchProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('sowp.bip.search_manager')) {
            return;
        }

        $definition = $container->findDefinition('sowp.bip.search_manager');
        $taggedServices = $container->findTaggedServiceIds('sowp.bip.search_result_provider.orm');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addProvider', array(new Reference($id)));
        }
    }
}