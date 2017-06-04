<?php
namespace Sowp\ApiBundle\Response;

use JMS\Serializer\Annotation as JMS_Annotation;

/**
 * Class Link
 * @package Sowp\ApiBundle\Response
 * @JMS_Annotation\ExclusionPolicy("NONE")
 */
class Link
{
    /**
     * @var string
     */
    private $rel;

    /**
     * @var string
     */
    private $href;

    public function __construct($rel = '', $href = '')
    {
        $this->setRel($rel);
        $this->setHref($href);
    }

    /**
     * @return string
     */
    public function getRel(): string
    {
        return $this->rel;
    }

    /**
     * @param string $rel
     */
    public function setRel(string $rel)
    {
        $this->rel = $rel;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @param string $href
     */
    public function setHref(string $href)
    {
        $this->href = $href;
    }


}