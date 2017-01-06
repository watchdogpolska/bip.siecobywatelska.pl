<?php
/**
 * Created by PhpStorm.
 * User: andrzej
 * Date: 30.12.16
 * Time: 08:50
 */

namespace Sowp\MenuBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ItemResolverPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sowp.menu.resolver_chain')) {
            return;
        }
        $provider = $container->getDefinition('sowp.menu.resolver_chain');
        $taggedServiceIds = $container->findTaggedServiceIds('sowp.menu.resolver');
        foreach ($taggedServiceIds as $id => $attr) {
            $provider->addMethodCall('addResolver', array(new Reference($id)));
        }
    }
}