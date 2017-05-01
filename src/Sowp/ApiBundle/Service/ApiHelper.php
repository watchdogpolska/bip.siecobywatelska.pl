<?php

namespace Sowp\ApiBundle\Service;

use JMS\Serializer\Serializer;
use Sowp\ApiBundle\Tests\TestEntity;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Component\HttpFoundation\Request;

class ApiHelper
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->setSerializer($serializer);
    }

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }


    public function deserializeRequest(Request $request)
    {

    }

    public function serializeCollection(Collection $collection)
    {
        return $this->serializer->serialize($collection, 'json');
    }


    public function serializeNews(News $news)
    {

    }

}