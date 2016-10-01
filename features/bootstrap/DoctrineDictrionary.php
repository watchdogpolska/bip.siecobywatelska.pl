<?php

use Symfony\Component\DependencyInjection\ContainerInterface;

trait DoctrineDictrionary
{
    public function getManager()
    {
        /** @var ContainerInterface $container */
        $container = $this->getContainer();

        return $container->get('doctrine.orm.entity_manager');
    }
}
