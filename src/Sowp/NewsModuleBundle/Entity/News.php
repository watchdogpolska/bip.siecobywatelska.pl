<?php

namespace Sowp\NewsModuleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SimpleThings\EntityAudit\Mapping\Annotation as Audit;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Sowp\NewsModuleBundle\Entity\NewsRepository")
 * @ORM\Table(name="news")
 * @Audit\Auditable()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class News
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
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=255, unique=true, nullable=false)
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="Sowp\CollectionBundle\Entity\Collection", inversedBy="news")
     */
    private $collections;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     */
    private $content;

    /**
     * @ORM\Column(name="attachments", type="json_array", nullable=true)
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

    /**
     * @var string
     *
     * @ORM\Column(name="modifynote", type="text", length=10000, nullable=true)
     */
    private $modifyNote;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

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
     * @return bool
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
     * Add collection.
     *
     * @param \NewsModuleBundle\Entity\Collection $collection
     *
     * @return News
     */
    public function addCollection(\Sowp\NewsModuleBundle\Entity\Collection $collection)
    {
        if (!$this->collections->contains($collection)) {
            $this->collections[] = $collection;
        }

        return $this;
    }

    /**
     * Remove collection.
     *
     * @param \NewsModuleBundle\Entity\Collection $collection
     */
    public function removeCollection(\Sowp\NewsModuleBundle\Entity\Collection $collection)
    {
        $this->collections->removeElement($collection);
    }

    /**
     * Set attachments.
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
     * Get attachments.
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Get collections.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * Set modifyNote.
     *
     * @param string $modifyNote
     *
     * @return News
     */
    public function setModifyNote($modifyNote)
    {
        $this->modifyNote = $modifyNote;

        return $this;
    }

    /**
     * Get modifyNote.
     *
     * @return string
     */
    public function getModifyNote()
    {
        return $this->modifyNote;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}
