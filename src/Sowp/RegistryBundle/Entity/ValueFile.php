<?php

namespace Sowp\RegistryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Sowp\RegistryBundle\Repository\ValueRepository")
 * @Gedmo\Uploadable
 */
class ValueFile extends Value
{
    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFileName
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFileMimeType
     */
    private $mimeType;

    /**
     * @ORM\Column(type="decimal")
     * @Gedmo\UploadableFileSize
     */
    private $size;


    /**
     * Set path
     *
     * @param string $path
     *
     * @return ValueFile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ValueFile
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return ValueFile
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return ValueFile
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getType()
    {
        return Attribute::TYPE_FILE;
    }
}
