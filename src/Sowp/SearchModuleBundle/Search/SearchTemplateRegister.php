<?php

namespace Sowp\SearchModuleBundle\Search;

class SearchTemplateRegister
{
    private $elements;

    public function __construct()
    {
        $this->elements = new \ArrayObject();
    }

    /**
     * @return array
     */
    public function getElements():array
    {
        return $this->elements->getArrayCopy();
    }

    public function addElement($index, $element):SearchTemplateRegister
    {
        $this->elements->offsetSet($index, $element);
        return $this;
    }

    public function hasElement($elementIndex):bool
    {
        return $this->elements->offsetExists($elementIndex);
    }

    public function getElement($elementIndex)
    {
        if (!$this->hasElement($elementIndex)) {
            return false;
        }

        return $this->elements->offsetGet($elementIndex);
    }
}