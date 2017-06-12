<?php
namespace Sowp\ApiBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Sowp\ApiBundle\Service\ApiHelper;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;

class LinkSelfSerializationSubscriber implements EventSubscriberInterface
{
    private $helper;

    public function __construct(ApiHelper $helper)
    {
        $this->helper = $helper;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'postSerialize',
                'format' => 'json',
            ]
        ];
    }

    public function postSerialize(ObjectEvent $oe)
    {
        if (!\in_array(\get_class($oe->getObject()), [
            Article::class,
            Collection::class,
            News::class
        ])) {
            return;
        }

        $oe->getVisitor()->addData('links', [
            'self' => $this->helper->getShowLinkForEntity($oe->getObject())
        ]);
    }

}