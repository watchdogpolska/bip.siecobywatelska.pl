<?php

namespace Sowp\NewsModuleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Sowp\NewsModuleBundle\Entity\CollectionRepository")
 * @ORM\Table(name="news_collection")
 */
class Collection
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true, nullable=false)
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean", nullable=false)
     */
    private $public;

    /**
     * @var Sowp\NewsModuleBundle\Entity\Collection
     *
     * @ORM\OneToMany(targetEntity="Sowp\NewsModuleBundle\Entity\Collection", mappedBy="childCollections")
     */
    private $parentCollection;

    /**
     * @var Sowp\NewsModuleBundle\Entity\Collection
     *
     * @ORM\ManyToOne(targetEntity="Sowp\NewsModuleBundle\Entity\Collection", inversedBy="parentCollection")
     */
    private $childCollections;

    /**
     * @ORM\ManyToMany(targetEntity="Sowp\NewsModuleBundle\Entity\News", mappedBy="collections")
     */
    private $news;

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
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
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
     * @ORM\JoinColumn(name="modified_by", referencedColumnName="id")
     */
    private $modifiedBy;

    public function __construct()
    {
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->childCollections = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Collection
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set public.
     *
     * @param bool $public
     *
     * @return Collection
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public.
     *
     * @return bool
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Collection
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt.
     *
     * @param \DateTime $modifiedAt
     *
     * @return Collection
     */
    public function setModifiedAt(\DateTime $modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt.
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Collection
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set modifiedBy.
     *
     * @param \AppBundle\Entity\User $modifiedBy
     *
     * @return Collection
     */
    public function setModifiedBy(\AppBundle\Entity\User $modifiedBy = null)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Get modifiedBy.
     *
     * @return \AppBundle\Entity\User
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Add news.
     *
     * @param \NewsModuleBundle\Collection $news
     *
     * @return Collection
     */
    public function addNews(\Sowp\NewsModuleBundle\Entity\News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news.
     *
     * @param \NewsModuleBundle\Collection $news
     */
    public function removeNews(\Sowp\NewsModuleBundle\Entity\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Add parentCollection.
     *
     * @param \Sowp\NewsModuleBundle\Entity\Collection $parentCollection
     *
     * @return Collection
     */
    public function addParentCollection(\Sowp\NewsModuleBundle\Entity\Collection $parentCollection)
    {
        $this->parentCollection[] = $parentCollection;

        return $this;
    }

    /**
     * Remove parentCollection.
     *
     * @param \Sowp\NewsModuleBundle\Entity\Collection $parentCollection
     */
    public function removeParentCollection(\Sowp\NewsModuleBundle\Entity\Collection $parentCollection)
    {
        $this->parentCollection->removeElement($parentCollection);
    }

    /**
     * Get parentCollection.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParentCollection()
    {
        return $this->parentCollection;
    }

    /**
     * Set childCollections.
     *
     * @param \Sowp\NewsModuleBundle\Entity\Collection $childCollections
     *
     * @return Collection
     */
    public function setChildCollections(\Sowp\NewsModuleBundle\Entity\Collection $childCollections = null)
    {
        $this->childCollections = $childCollections;

        return $this;
    }

    /**
     * Get childCollections.
     *
     * @return \Sowp\NewsModuleBundle\Entity\Collection
     */
    public function getChildCollections()
    {
        return $this->childCollections;
    }
}
