<?php

namespace Sowp\ArticleBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Sowp\ArticleBundle\Entity\Article;
use Sowp\ArticleBundle\FileUploader;
use Sowp\NewsModuleBundle\Entity\News;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentSubscriber implements EventSubscriber
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
        );
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!($entity instanceof Article) && !($entity instanceof News)) {
            return;
        }

        if (!$args->hasChangedField('attachments')) {
            return;
        }

        $oldValue = $args->getOldValue('attachments');
        $newValue = $args->getNewValue('attachments');
        if (empty($newValue)) {
            $newValue = array();
        }
        $this->handleAttachments($oldValue, $newValue, $entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!($entity instanceof Article) && !($entity instanceof News)) {
            return;
        }
        $newValue = $entity->getAttachments();
        if (empty($newValue)) {
            $newValue = array();
        }
        $this->handleAttachments([], $newValue, $entity);
    }

    private function handleAttachments(array $oldValue, array $newValue, Article $entity)
    {
        $new_attachment = array_filter($newValue, function ($var) {
            return $var['file'] instanceof UploadedFile;
        });

        $new_attachment = $this->handleUploads($new_attachment);

        $current_old_files = array_filter($newValue, function ($attachment) {
            return !$attachment['file'] instanceof UploadedFile;
        });
        $old_files = array_intersect_key($oldValue, $current_old_files);

        $entity->setAttachments(array_merge($new_attachment, $old_files));
    }

    private function handleUploads($attachments)
    {
        if (empty($attachments)) {
            return $attachments;
        }

        foreach ($attachments as &$attachment) {
            /** @var UploadedFile $file */
            $file = $attachment['file'];
            $attachment['file'] = $this->uploader->upload($file);

            if (empty(trim($attachment['name']))) {
                $attachment['name'] = $attachment['file']['filename'];
            }
        }

        return $attachments;
    }
}
