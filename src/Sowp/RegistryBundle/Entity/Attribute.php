<?php

namespace Sowp\RegistryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Sowp\RegistryBundle\Repository\AttributeRepository")
 * @ORM\Table()
 */
class Attribute
{
    const TYPE_TEXT = "text";
    const TYPE_FILE = "file";
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $type = self::TYPE_TEXT;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Sowp\RegistryBundle\Entity\Registry", inversedBy="attributes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $registry;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Attribute
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
     * Set type
     *
     * @param string $type
     *
     * @return Attribute
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Attribute
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set registry
     *
     * @param \Sowp\RegistryBundle\Entity\Registry $registry
     *
     * @return Attribute
     */
    public function setRegistry(\Sowp\RegistryBundle\Entity\Registry $registry = null)
    {
        $this->registry = $registry;

        return $this;
    }

    /**
     * Get registry
     *
     * @return \Sowp\RegistryBundle\Entity\Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function createValue(){
        if($this->type== self::TYPE_FILE) {
            $value = new ValueFile();
        } else {
            $value = new ValueText();
        }
        $value->setAttribute($this);

        return $value;
    }
}
