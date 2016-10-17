<?php

namespace Sowp\RegistryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"text" = "ValueText", "file" = "ValueFile"})
 */
abstract class Value
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sowp\RegistryBundle\Entity\Row", inversedBy="values", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
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

    public abstract function getType();
}
