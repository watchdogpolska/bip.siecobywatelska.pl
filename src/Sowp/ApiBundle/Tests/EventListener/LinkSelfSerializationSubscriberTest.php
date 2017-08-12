<?php
namespace Sowp\ApiBundle\Tests\EventListener;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use JMS\Serializer\ContextFactory\DefaultSerializationContextFactory;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Sowp\ApiBundle\EventListener\AttachmentsLinksSerializationSubscriber;
use Sowp\ApiBundle\EventListener\LinkSelfSerializationSubscriber;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;

class LinkSelfSerializationSubscriberTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSubscribedEvents()
    {
        $events = LinkSelfSerializationSubscriber::getSubscribedEvents();

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

        $someObject = new \StdClass;

        $oeS = new ObjectEvent($context, $someObject, [
            'name' => \StdClass::class,
            'params' => []
        ]);

        $alss = new LinkSelfSerializationSubscriber(
            $this->container->get('api_helper')
        );

        //subscriber returns null on invalid object type
        $this->assertNull($alss->postSerialize($oeS), "Expect null");

        //return false without initialized Context but on proper object
        $this->assertFalse($alss->postSerialize($oeC), "Expect false");
        $this->assertFalse($alss->postSerialize($oeN), "Expect false");
        $this->assertFalse($alss->postSerialize($oeA), "Expect false");
    }

    public function testAddSerializationField()
    {
        $n = new News();

        $n->setContent("content");
        $n->setPinned(true);
        $n->setTitle(
            \sprintf("test title %s", \uniqid())
        );
        $this->em->persist($n);
        $this->em->flush($n);
        $this->em->refresh($n);

        $serializer = $this->container->get('jms_serializer');
        $n_serialized = $serializer->serialize($n, 'json');
        $n_deserialized = $serializer->deserialize(
            $n_serialized,
            'array',
            'json'
        );

        $this->assertArrayHasKey(
            'links',
            $n_deserialized,
            "Expected key 'links'"
        );
        $this->assertTrue(
            \is_array($n_deserialized['links']),
            "Attachments expected to be array"
        );

        $link = $n_deserialized['links'];

        $url = \parse_url($link['self']);

        $this->assertNotFalse(
            \strpos($url['path'], (string)$n->getId()),
            "Invalid url"
        );
    }
}