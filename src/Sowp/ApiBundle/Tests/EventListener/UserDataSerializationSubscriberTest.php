<?php
namespace Sowp\ApiBundle\Tests\EventListener;

use AppBundle\Entity\User;
use AppBundle\Tests\ApiUtils\ApiTestCase;
use JMS\Serializer\ContextFactory\DefaultSerializationContextFactory;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Sowp\ApiBundle\EventListener\UserDataSerializationSubscriber;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;

class UserDataSerializationSubscriberTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSubscribedEvents()
    {
        $events = UserDataSerializationSubscriber::getSubscribedEvents();

        $this->assertTrue(\is_array($events), "Subscribed events expected to be array");
        $this->assertEquals(1, \count($events), "Subscribed events count expected to be 1");
        $this->assertArrayHasKey('event', $events[0], "Subscribed events expected to has 'event' key");
        $this->assertEquals(
            $events[0]['event'],
            'serializer.post_serialize',
            "Subscribed events expected to contain diffrent event"
        );
    }

    public function testSubscribedObjects()
    {
        $contextFactory = new DefaultSerializationContextFactory();
        $context = $contextFactory->createSerializationContext();

        $c = $this->createCollection();
        $n = $this->createNews();
        $a = $this->createArticle();
        $u = new User();

        $oeN = new ObjectEvent($context, $n, [
            'name' => News::class,
            'params' => []
        ]);

        $oeC = new ObjectEvent($context, $c, [
            'name' => Collection::class,
            'params' => []
        ]);

        $oeA = new ObjectEvent($context, $a, [
            'name' => Article::class,
            'params' => []
        ]);

        $oeU = new ObjectEvent($context, $u, [
            'name' => User::class,
            'params' => []
        ]);

        $udss = new UserDataSerializationSubscriber();

        //subscriber returns null on invalid object type
        $this->assertNull($udss->postSerialize($oeU), "Expect null");

        //return false without initialized Context but on proper object
        $this->assertFalse($udss->postSerialize($oeN), "Expect false");
        $this->assertFalse($udss->postSerialize($oeA), "Expect false");
        $this->assertFalse($udss->postSerialize($oeC), "Expect False");
    }

    public function testAddSerializationField()
    {
        $news = new News();
        $user = new User();

        //configure user
        $user->setUsername("test_user" . \uniqid("", true));
        $user->setEmail(\uniqid("", true) . "_email@example.com");
        $user->setPassword("supersecretpassword");

        //save it
        $this->em->persist($user);
        $this->em->flush($user);
        $this->em->refresh($user);

        //configure news, bind to user
        $news->setContent("content");
        $news->setPinned(true);
        $news->setTitle(
            \sprintf("test title %s", \uniqid())
        );
        $news->setCreatedBy($user);
        $news->setCreatedAt(
            new \DateTime('- 1 DAY')
        );
        $news->setModifiedBy($user);
        $news->setModifiedAt(new \DateTime());

        //save
        $this->em->persist($news);
        $this->em->flush($news);
        $this->em->refresh($news);

        $serializer = $this->container->get('jms_serializer');
        $news_serialized = $serializer->serialize($news, 'json');
        $news_deserialized = $serializer->deserialize(
            $news_serialized,
            'array',
            'json'
        );

        $this->assertArrayHasKey(
            'modified_by',
            $news_deserialized,
            "modified_by serialization key expected in entity object"
        );
        $this->assertArrayHasKey(
            'created_by',
            $news_deserialized,
            "created_by serialization key expected in entity object"
        );

        $this->assertTrue(
            \is_array($news_deserialized['created_by']) &&
            \is_array($news_deserialized['modified_by']),
            "user entity fields should be arrays"
        );


        $this->assertArrayHasKey('id', $news_deserialized['created_by'], "user id serialization");
        $this->assertArrayHasKey('username', $news_deserialized['created_by'], "user name serialization");
        $this->assertArrayHasKey('id', $news_deserialized['modified_by'], "user id serialization");
        $this->assertArrayHasKey('username', $news_deserialized['modified_by'], "user name serialization");

        $this->assertTrue(
            $news_deserialized['created_by']['id'] === $user->getId() &&
            $news_deserialized['created_by']['username'] === $user->getUsername() &&
            $news_deserialized['modified_by']['id'] === $user->getId() &&
            $news_deserialized['modified_by']['username'] === $user->getUsername(),
            "username or id problem during user concerning fields"
        );

        //clean
        $this->em->remove($news);
        $this->em->remove($user);
        $this->em->flush();
    }
}