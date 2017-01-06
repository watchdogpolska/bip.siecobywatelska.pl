<?php

namespace Sowp\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Sowp\MenuBundle\Entity\MenuItemRepository")
 * @ORM\Table(name="sowp_menu_item")
 */
class MenuItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=140, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private $object_clazz;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $object_id;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Sowp\MenuBundle\Entity\MenuItem")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * One parent Menu Item has Many Child Menu Items.
     * @ORM\OneToMany(targetEntity="Sowp\MenuBundle\Entity\MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"lft"="ASC"})
     */
    private $children;

    /**
     * Many child Items have One parent Menu Item.
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Sowp\MenuBundle\Entity\MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    public function __construct() {
        $this->children = new ArrayCollection();
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
     * @return MenuItem
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
     * Set url
     *
     * @param string $url
     *
     * @return MenuItem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set object class
     *
     * @param string $clazz
     *
     * @return MenuItem
     */
    public function setObjectClazz($clazz)
    {
        $this->object_clazz = $clazz;

        return $this;
    }

    /**
     * Get object class
     *
     * @return string
     */
    public function getObjectClazz()
    {
        return $this->object_clazz;
    }

    /**
     * Set object id
     *
     * @param int $id
     *
     * @return MenuItem
     */
    public function setObjectId($id)
    {
        $this->object_id = $id;

        return $this;
    }

    /**
     * Get object id
     *
     * @return int
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * Add child
     *
     * @param MenuItem $child
     *
     * @return MenuItem
     */
    public function addChild(MenuItem $child)
    {
        $this->children[] = $child;
        $child->setParent($this);

        return $this;
    }

    /**
     * Remove child
     *
     * @param MenuItem $child
     */
    public function removeChild(MenuItem $child)
    {
        $this->children->removeElement($child);
        $child->setParent(null);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param MenuItem $parent
     *
     * @return MenuItem
     */
    public function setParent(MenuItem $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get root
     *
     * @return MenuItem
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function __toString()
    {
        return $this->name;
    }
}
