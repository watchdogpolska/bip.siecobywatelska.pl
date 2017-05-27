<?php

namespace Sowp\ApiBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Sowp\ApiBundle\Tests\TestEntity;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ApiHelper
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(
        Serializer $serializer,
        RouterInterface $router,
        EntityManager $entityManager
    )
    {
        $this->setSerializer($serializer);
        $this->setRouter($router);
        $this->setEm($entityManager);
    }

    /**
     * @return EntityManager
     */
    public function getEm(): EntityManager
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
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

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function createApiResponse()
    {

    }

    public function createErrorResponse($code, $message, array $links)
    {

    }
}