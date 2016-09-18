<?php
namespace NewsModuleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 */
class News {
    
    /**
     *
     * @var int
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="title", type="string", length=255, unique=true, nullable=false)
     */
    private $title;

    /**
     * @ManyToMany(targetEntity="NewsModuleBundle\Entity\Collection", inversedBy="news")
     * @JoinTable(name="news_collection")
     */
    private $collections;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;
    
    /**
     * some kind of array
     */
    private $attachments;
    
    /**
     * @var bool
     * 
     * @ORM\Column(name="pinned", type="boolean", nullable=false)
     */
    private $pinned;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * @var \AppBundle\Entity\User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */    
    private $createdBy;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;
    
    /**
     * @var \AppBundle\Entity\User
     *
     * @Gedmo\Blameable(on="change")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modified_by", referencedColumnName="id")
     * })
     */
    private $modifiedBy;
    
    public function __construct()
    {
        $this->collections = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollections()
    {
        return $this->collections;
    }

    public function setCollections($collection)
    {
        $this->collections = $collection;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function getPinned()
    {
        return $this->pinned;
    }
    
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;
        return $this;
    }

    /**
     * 
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy(\AppBundle\Entity\User $user)
    {
        $this->createdBy = $user;
        return $this;
    }

    /**
     * 
     * @return \AppBundle\Entity\User
     */    
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(\AppBundle\Entity\User $user)
    {
        $this->modifiedBy = $user;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $leTime)
    {
        $this->createdAt = $leTime;
        return $this;
    }

    /**
     * @return \DateTime
     */    
    public function getModifiedAt()
    {
       return $this->modifiedAt; 
    }

    public function setModifiedAt(\DateTime $leTime)
    {
        $this->modifiedAt = $leTime;
        return $this;
    }    
}
