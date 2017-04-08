<?php

namespace Sowp\ApiBundle\Service;

class ApiHelper
{
    private $serializer;

    public function __construct($serializer)
    {
        $this->serializer = $serializer;
    }
}