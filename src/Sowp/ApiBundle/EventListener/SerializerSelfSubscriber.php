<?php
namespace Sowp\ApiBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;

class SerializerSelfSubscriber implements EventSubscriberInterface
{
    private $router;
    private $apiHelper;

    public function __construct()
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json',
            ]
        ];
    }

}