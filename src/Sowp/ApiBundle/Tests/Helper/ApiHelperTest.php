<?php

namespace Sowp\ApiBundle\Tests\Helper;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\ApiBundle\Response\Link;
use Sowp\ApiBundle\Service\ApiHelper;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\NewsModuleBundle\Entity\News;

class ApiHelperTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testConvertLinksArray()
    {
        $helper = $this->container->get('api_helper');

        $this->assertTrue(
            $helper instanceof ApiHelper,
            "ApiHelper expected from 'api_helper' service"
        );

        //mock links array
        $links = [
            'next' => 'http://link/next',
            'previous' => 'http://link/previous',
            'current' => 'http://link/current'
        ];

        $linksAfter = $helper->convertLinksArray($links);

        $this->assertEquals(
            \count($linksAfter),
            \count($links),
            "Link count expected to be equal"
        );

        foreach ($links as $relFromArray => $hrefFromArray) {

            $this->assertArrayHasKey(
                $relFromArray,
                $linksAfter,
                "link index $relFromArray lacking in result object"
            );

            $this->assertInstanceOf(
                Link::class,
                $linksAfter[$relFromArray],
                "Link class expected"
            );

            $this->assertEquals(
                $linksAfter[$relFromArray]->getRel(),
                $relFromArray,
                "Rel params not match"
            );

            $this->assertEquals(
                $linksAfter[$relFromArray]->getHref(),
                $hrefFromArray,
                "Href params not match"
            );
        }
    }

    public function testGetShowLinksForEntity()
    {
        $helper = $this->container->get('api_helper');
        $c = $this->createCollection();
        $a = $this->createArticle();
        $n = $this->createNews();
        $wrong = new Link();

        $this->assertTrue(
            \is_string($helper->getShowLinkForEntity($c)),
            "Wrong object class"
        );
        $this->assertTrue(
            \is_string($helper->getShowLinkForEntity($a)),
            "Wrong object class"
        );
        $this->assertTrue(
            \is_string($helper->getShowLinkForEntity($n)),
            "Wrong object class"
        );

        try {
            $helper->getShowLinkForEntity($wrong);
            $cant_be_here = true;
        } catch (\Exception $e) {
            $txt = $e->getMessage();
            $must_be_here = true;
        }

        $this->assertFalse(isset($cant_be_here), "You \$cant_be_here =]");
        $this->assertTrue(isset($must_be_here), "You \$must_be_here");
        $this->assertTrue($must_be_here, "You \$must_be_here value");
    }

    public function testCreateAttachmentsLinks()
    {
        $helper = $this->container->get('api_helper');

        //mocking adding attachments
        $attachmentsMock = [ //attachments
            [
                'name' => 'test1',
                'file' => [
                    'filename' => 'test1.jpg'
                ]
            ], //attachment
        ];

    $links = $helper->createAttachmentsLinks($attachmentsMock);

    $fileName = 'test1';
    $this->assertTrue(
        $this->apiStringContains($fileName, $links[0]['file'])
    );

    }
}