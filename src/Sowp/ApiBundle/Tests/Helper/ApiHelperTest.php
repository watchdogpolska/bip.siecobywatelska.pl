<?php

namespace Sowp\ApiBundle\Tests\Helper;

use AppBundle\Tests\ApiUtils\ApiTestCase;
use Sowp\ApiBundle\Response\Link;
use Sowp\ApiBundle\Service\ApiHelper;

class ApiHelperTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCreateLinksArray()
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
}