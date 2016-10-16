<?php

namespace Sowp\RegistryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Sowp\RegistryBundle\Repository\RowRepository")
 * @ORM\Table()
 */
class Row
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="Sowp\RegistryBundle\Entity\Registry", inversedBy="rows", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $registry;

    /**
     * @ORM\OneToMany(targetEntity="Sowp\RegistryBundle\Entity\Value", mappedBy="row", fetch="EAGER", cascade={"persist"})
     */
    private $values;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set registry
     *
     * @param \Sowp\RegistryBundle\Entity\Registry $registry
     *
     * @return Row
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

    /**
     * Add value
     *
     * @param \Sowp\RegistryBundle\Entity\Value $value
     *
     * @return Row
     */
    public function addValue(\Sowp\RegistryBundle\Entity\Value $value)
    {
        $value->setRow($this);
        $this->values[] = $value;

        return $this;
    }

    /**
     * Remove value
     *
     * @param \Sowp\RegistryBundle\Entity\Value $value
     */
    public function removeValue(\Sowp\RegistryBundle\Entity\Value $value)
    {
        $value->setRow(null);
        $this->values->removeElement($value);
    }

    /**
     * Get values
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValues()
    {
        return $this->values;
    }

    public function __toString()
    {
        return "Row: " . $this->getId();
    }
}
