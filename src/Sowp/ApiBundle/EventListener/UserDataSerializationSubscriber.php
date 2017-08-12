<?php
namespace Sowp\ApiBundle\EventListener;

use AppBundle\Entity\User;
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
            return null;
        }

        $visitor = $oe->getVisitor();

        if (!$visitor) {
            return false;
        }

        $a = function ($user) {
            return [
                'id' => $user->getId(),
                'username' => $user->getUsername()
            ];
        };

        if (\is_object($oe->getObject()->getCreatedBy())) {
            $userCreator = $oe->getObject()->getCreatedBy();
        }

        if (\is_object($oe->getObject()->getModifiedBy())) {
            $userModifier = $oe->getObject()->getModifiedBy();
            $oe->getVisitor()->addData('modified_by', $a($userModifier));
        }

        if (!isset($userCreator)) {
            $userCreator = new User();
            $userCreator->setUsername('anon');
        }

        $oe->getVisitor()->addData('created_by', $a($userCreator));

        return true;
    }
}