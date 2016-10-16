<?php

namespace Sowp\RegistryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Sowp\RegistryBundle\Repository\RegistryRepository")
 * @ORM\Table()
 */
class Registry
{
    const TYPE_TABLE = "table";
    const TYPE_LIST = "list";

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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type = self::TYPE_TABLE;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Sowp\RegistryBundle\Entity\Attribute", mappedBy="registry", cascade={"persist"}, fetch="EAGER")
     */
    private $attributes;

    /**
     * @ORM\OneToMany(targetEntity="Sowp\RegistryBundle\Entity\Row", mappedBy="registry")
     */
    private $rows;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rows = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Registry
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
     * @return Registry
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
     * @return Registry
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
     * Add attribute
     *
     * @param \Sowp\RegistryBundle\Entity\Attribute $attribute
     *
     * @return Registry
     */
    public function addAttribute(\Sowp\RegistryBundle\Entity\Attribute $attribute)
    {
        $attribute->setRegistry($this);
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute
     *
     * @param \Sowp\RegistryBundle\Entity\Attribute $attribute
     */
    public function removeAttribute(\Sowp\RegistryBundle\Entity\Attribute $attribute)
    {
        $attribute->setRegistry(null);
        $this->attributes->removeElement($attribute);
    }

    /**
     * Get attributes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Add row
     *
     * @param \Sowp\RegistryBundle\Entity\Row $row
     *
     * @return Registry
     */
    public function addRow(\Sowp\RegistryBundle\Entity\Row $row)
    {
        $row->setRegistry($this);
        $this->rows[] = $row;

        return $this;
    }

    /**
     * Remove row
     *
     * @param \Sowp\RegistryBundle\Entity\Row $row
     */
    public function removeRow(\Sowp\RegistryBundle\Entity\Row $row)
    {
        $row->setRegistry(null);
        $this->rows->removeElement($row);
    }

    /**
     * Get rows
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRows()
    {
        return $this->rows;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Registry
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
