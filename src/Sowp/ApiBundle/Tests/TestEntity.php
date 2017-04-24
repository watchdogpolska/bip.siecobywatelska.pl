<?php
namespace Sowp\ApiBundle\Tests;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
/**
 * Class TestEntity
 * @package Sowp\ApiBundle\Tests
 *
 * @ExclusionPolicy("all")
 */
class TestEntity
{
    /**
     * @var int
     * @Expose()
     */
    private $id;
    /**
     * @var string
     * @Expose()
     */
    private $title;
    /**
     * @var string
     */
    private $content;

    public function __construct()
    {
        $this->id = \mt_rand();
        $this->title = 'test';
        $this->content = 'test';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }


}