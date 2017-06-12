<?php

namespace Sowp\NewsModuleBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\NewsModuleBundle\Entity\Collection;

class NewsControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;


    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
