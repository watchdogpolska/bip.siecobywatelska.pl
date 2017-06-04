<?php
namespace Sowp\ApiBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Sowp\ApiBundle\Service\ApiHelper;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;

class UserDataSerializationSubscriber implements EventSubscriberInterface
{
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

        $a = function ($user) {
            return [
                'id' => $user->getId(),
                'username' => $user->getUsername()
            ];
        };

        if (\is_object($oe->getObject()->getCreatedBy())) {
            $user = $oe->getObject()->getCreatedBy();
            $oe->getVisitor()->addData('created_by', $a($user));
        }

        if (\is_object($oe->getObject()->getModifiedBy())) {
            $user = $oe->getObject()->getModifiedBy();
            $oe->getVisitor()->addData('modified_by', $a($user));
        }
    }
}