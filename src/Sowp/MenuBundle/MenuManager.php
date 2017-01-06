<?php

namespace Sowp\MenuBundle;


use Doctrine\ORM\EntityManager;
use Sowp\MenuBundle\Entity\MenuItem;
use Sowp\MenuBundle\Menu\AttachableElement;
use Sowp\MenuBundle\Menu\ItemResolverChain;

class MenuManager
{
    protected $em;
    function __construct(EntityManager $em, ItemResolverChain $resolverChain)
    {
        $this->em = $em;
        $this->resolverChain = $resolverChain;
    }

    public function addItem(AttachableElement $item, $flush = false)
    {
        $repo = $this->em->getRepository(MenuItem::class);
        $root = $repo->getRootNodes()[0];
        $item = new MenuItem();
        $item->setParent($root);
        $item->setName((string) $item);
        $item->setObjectClazz(get_class($item));
        $item->setObjectId($item->getId());
        if($this->resolverChain->support($item)){
            $item->setUrl($this->resolverChain->resolve($item));
        }
        $this->em->persist($item);
        if($flush){
            $this->em->flush();
        }
    }
}