<?php
/**
 * Created by PhpStorm.
 * User: andrzej
 * Date: 30.12.16
 * Time: 08:41
 */

namespace Sowp\MenuBundle\Menu;


interface ItemResolver
{
    // Generate a url for element
    public function resolve(AttachableElement $element);

    public function support(AttachableElement $element);
}