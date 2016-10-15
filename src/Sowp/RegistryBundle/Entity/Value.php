<?php

namespace Sowp\RegistryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Sowp\RegistryBundle\Repository\ValueRepository")
 * @ORM\Table()
 */
class Value
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Sowp\RegistryBundle\Entity\Row", inversedBy="values", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $row;

    /**
     * @ORM\ManyToOne(targetEntity="Sowp\RegistryBundle\Entity\Attribute", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $attribute;

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
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->getAttribute()->getName();
    }
    /**
     * Set value
     *
     * @param string $value
     *
     * @return Value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set row
     *
     * @param \Sowp\RegistryBundle\Entity\Row $row
     *
     * @return Value
     */
    public function setRow(\Sowp\RegistryBundle\Entity\Row $row = null)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * Get row
     *
     * @return \Sowp\RegistryBundle\Entity\Row
     */
    public function getRow()
    {
        return $this->row;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Set attribute
     *
     * @param \Sowp\RegistryBundle\Entity\Attribute $attribute
     *
     * @return Value
     */
    public function setAttribute(\Sowp\RegistryBundle\Entity\Attribute $attribute = null)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute
     *
     * @return \Sowp\RegistryBundle\Entity\Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
}
