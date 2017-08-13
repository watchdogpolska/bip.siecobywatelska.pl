<?php
namespace Sowp\ApiBundle\Response;

use JMS\Serializer\Annotation as JMS_Annotation;

/**
 * Class ApiResponse
 * @package Sowp\ApiBundle\Response
 * @JMS_Annotation\ExclusionPolicy("NONE")
 */
class ApiResponse implements CodeInterface
{
    /**
     * @var int
     */
    private $responseCode;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string[]
     */
    private $links;

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @param int $responseCode
     */
    public function setResponseCode(int $responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return \string[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param \string[] $links
     */
    public function setLinks(array $links)
    {
        $this->links = $links;
    }
}