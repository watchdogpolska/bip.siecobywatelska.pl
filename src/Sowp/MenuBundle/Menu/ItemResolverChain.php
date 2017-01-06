<?php

namespace Sowp\MenuBundle\Menu;


class ItemResolverChain implements ItemResolver
{
    /** @var  ItemResolver[] $resolvers */
    private $resolvers;

    public function addResolver(ItemResolver $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    public function resolve(AttachableElement $element) {
        return $this->findResolver($element)->resolve($element);
    }

    public function support(AttachableElement $element)
    {
        return ($this->findResolver($element)) != null;
    }

    protected function findResolver(AttachableElement $element)
    {
        foreach ($this->resolvers as $resolver){
            if($resolver->support($element)){
                return $resolver;
            }
        }
        return null;
    }
}