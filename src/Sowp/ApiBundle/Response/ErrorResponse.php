<?php
namespace Sowp\ApiBundle\Response;

use JMS\Serializer\Annotation as JMS_Annotation;

/**
 * Class ErrorResponse
 * @package Sowp\ApiBundle\Response
 * @JMS_Annotation\ExclusionPolicy("NONE")
 */
class ErrorResponse implements CodeInterface
{
    /**
     * @var int
     */
    private $responseCode;

    /**
     * @var string
     */
    private $msg;

    /**
     * @var Link[]
     */
    private $links;

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param Link[] $links
     */
    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg)
    {
        $this->msg = $msg;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function setResponseCode($code)
    {
        $this->responseCode = $code;
    }
}