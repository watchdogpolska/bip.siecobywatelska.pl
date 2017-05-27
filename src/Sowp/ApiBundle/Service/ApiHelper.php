<?php

namespace Sowp\ApiBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Pagerfanta\Pagerfanta;
use Sowp\ApiBundle\Response\ApiResponse;
use Sowp\ApiBundle\Response\ErrorResponse;
use Sowp\ApiBundle\Response\Link;
use Sowp\ApiBundle\Tests\TestEntity;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
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

    public function createApiResponse($code, $data, array $links)
    {
        $obj = new ApiResponse();
        $obj->setResponseCode($code);
        $obj->setData($data);
        $obj->setLinks($this->convertLinksArray($links));

        $data_serialized = $this->getSerializer()->serialize($obj, 'json');

        return new Response($data_serialized, $code, [
            'content-type' => 'application/json'
        ]);
    }

    public function createErrorResponse($code, $message, array $links)
    {
        $obj = new ErrorResponse();
        $obj->setResponseCode($code);
        $obj->setMsg($message);
        $obj->setLinks($this->convertLinksArray($links));

        $data_serialized = $this->getSerializer()->serialize($obj, 'json');

        return new Response($data_serialized, $code, [
            'content-type' => 'application/json'
        ]);
    }

    public function generateNavLinks(Pagerfanta $pag, $key, $route)
    {
        $a = [];

        if ($pag->hasNextPage()) {
            $a['next'] = $this->getRouter()->generate($route, [
                    $key => $pag->getNextPage(),
                ],
            Router::ABSOLUTE_URL
            );
        }

        if ($pag->hasPreviousPage()) {
            $a['previous'] = $this->getRouter()->generate($route, [
                    $key => $pag->getPreviousPage(),
                ],
                Router::ABSOLUTE_URL
            );
        }

        $a['current'] = $this->getRouter()->generate($route, [
                $key => $pag->getCurrentPage(),
            ],
            Router::ABSOLUTE_URL
        );

        return $a;
    }

    private function convertLinksArray(array $links)
    {
        $a = [];

        foreach ($links as $rel => $href) {
            $a[] = new Link($rel, $href);
        }

        return $a;
    }
}