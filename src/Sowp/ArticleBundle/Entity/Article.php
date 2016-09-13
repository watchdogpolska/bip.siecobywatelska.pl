<?php

namespace Sowp\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Loggable;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\SoftDeleteable;

/**
 * Article.
 *
 * @ORM\Table(
 *     name="article",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="slug_UNIQUE", columns={"slug"})
 *     }, indexes={
 *         @ORM\Index(name="fk_article_user1_idx", columns={"modifited_by"}),
 *         @ORM\Index(name="fk_article_user2_idx", columns={"created_by"}),
 *         @ORM\Index(name="fk_article_collections1_idx", columns={"collections_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Sowp\ArticleBundle\Entity\ArticleRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Article implements Loggable, SoftDeleteable
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
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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
     * @ORM\Column(name="modifited_at", type="datetime", nullable=false)
     */
    private $modifitedAt;

    /**
     * @var \AppBundle\Entity\User
     *
     * @Gedmo\Blameable(on="change", field={"title", "content", "attachments", "editNote"})
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modifited_by", referencedColumnName="id")
     * })
     */
    private $modifitedBy;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="attachments", type="text", length=65535, nullable=true)
     */
    private $attachments;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="edit_note", type="string", length=255, nullable=false)
     */
    private $editNote;

    /**
     * @var \Sowp\ArticleBundle\Entity\Collection
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Sowp\ArticleBundle\Entity\Collection", inversedBy="articles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="collections_id", referencedColumnName="id")
     * })
     */
    private $collection;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

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
     * Set slug.
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Article
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
     * Set content.
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Article
     */
    public function setCreatedAt($createdAt)
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
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Article
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
     * Set modifitedAt.
     *
     * @param \DateTime $modifitedAt
     *
     * @return Article
     */
    public function setModifitedAt($modifitedAt)
    {
        $this->modifitedAt = $modifitedAt;

        return $this;
    }

    /**
     * Get modifitedAt.
     *
     * @return \DateTime
     */
    public function getModifitedAt()
    {
        return $this->modifitedAt;
    }

    /**
     * Set modifitedBy.
     *
     * @param \AppBundle\Entity\User $modifitedBy
     *
     * @return Article
     */
    public function setModifitedBy(\AppBundle\Entity\User $modifitedBy = null)
    {
        $this->modifitedBy = $modifitedBy;

        return $this;
    }

    /**
     * Get modifitedBy.
     *
     * @return \AppBundle\Entity\User
     */
    public function getModifitedBy()
    {
        return $this->modifitedBy;
    }

    /**
     * Set attachments.
     *
     * @param string $attachments
     *
     * @return Article
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Get attachments.
     *
     * @return string
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set editNote.
     *
     * @param string $editNote
     *
     * @return Article
     */
    public function setEditNote($editNote)
    {
        $this->editNote = $editNote;

        return $this;
    }

    /**
     * Get editNote.
     *
     * @return string
     */
    public function getEditNote()
    {
        return $this->editNote;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime $deletedAt
     *
     * @return Article
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * is deleted?
     *
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deletedAt != null;
    }

    /**
     * Set collections.
     *
     * @param \Sowp\ArticleBundle\Entity\Collection $collection
     *
     * @return Article
     */
    public function setCollection(\Sowp\ArticleBundle\Entity\Collection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collections.
     *
     * @return \Sowp\ArticleBundle\Entity\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
