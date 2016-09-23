<?php
namespace NewsModuleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="NewsModuleBundle\Entity\NewsRepository")
 * @ORM\Table(name="news")
 * @Gedmo\Loggable(logEntryClass="NewsModuleBundle\Entity\NewsLog")
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
     * @Gedmo\Versioned 
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="NewsModuleBundle\Entity\Collection", inversedBy="news")
     * @ORM\JoinTable(name="collection_news")
     */
    private $collections;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     * @Gedmo\Versioned
     */
    private $content;
    
    /**
     * @ORM\Column(name="attachments", type="array", nullable=true)
     * @Gedmo\Versioned
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
     * @Gedmo\Timestampable(on="create")
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
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;
    
    /**
     * @var \AppBundle\Entity\User
     *
     * @Gedmo\Blameable(on="change", field="modifiedBy")
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

    /**
     * Add collection
     *
     * @param \NewsModuleBundle\Entity\Collection $collection
     *
     * @return News
     */
    public function addCollection(\NewsModuleBundle\Entity\Collection $collection)
    {
        $this->collections[] = $collection;

        return $this;
    }

    /**
     * Remove collection
     *
     * @param \NewsModuleBundle\Entity\Collection $collection
     */
    public function removeCollection(\NewsModuleBundle\Entity\Collection $collection)
    {
        $this->collections->removeElement($collection);
    }


    /**
     * Set attachments
     *
     * @param array $attachments
     *
     * @return News
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Get attachments
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
}
