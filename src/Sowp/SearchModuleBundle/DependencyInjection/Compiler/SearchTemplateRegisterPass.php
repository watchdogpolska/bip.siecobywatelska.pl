<?php

namespace Sowp\SearchModuleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SearchTemplateRegisterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('sowp.bip.search_templates_register')) {
            return;
        }

        $definition = $container->findDefinition('sowp.bip.search_templates_register');
        $taggedServices = $container->findTaggedServiceIds('sowp.bip.search_template');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addElement',
                array($container->get($id)->getIndex(), new Reference($id))
            );
        }
    }
}
