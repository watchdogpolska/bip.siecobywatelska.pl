<?php

namespace Sowp\NewsModuleBundle\EventListener\Doctrine;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Sowp\NewsModuleBundle\Entity\News;

/**
 * @class AttachmentUploadListener
 * handles News attachments
 */
class AttachmentUploadListener
{
    private $uploadPath;

    public function __construct($path)
    {
        $this->uploadPath = $path;

        if (!$this->uploadPath ||
            !is_dir($this->uploadPath) ||
            !is_writable($this->uploadPath)
        ) {
            throw new \Exception("parameter 'sowp_news_module_upload_path' need adjustment");
        }
    }

    /**
     * aiming at initial persist.
     *
     * @return bool
     *
     * @param LifecycleEventArgs $arg
     */
    public function prePersist(LifecycleEventArgs $arg)
    {
        $entity = $arg->getEntity();

        if (!($entity instanceof News)) {
            return false;
        }

        $preUploadAttachments = $entity->getAttachments();
        $postUploadAttachments = $this->upload($preUploadAttachments);

        $entity->setAttachments($postUploadAttachments);

        return true;
    }

    /**
     * triggered at updating ($em->fulsh() on already existing item).
     *
     * @return bool
     *
     * @param PreUpdateEventArgs $arg
     */
    public function preUpdate(PreUpdateEventArgs $arg)
    {
        $entity = $arg->getEntity();

        if (!($entity instanceof News)) {
            return false;
        }

        $oldAttachmentsValue = $arg->getOldValue('attachments');
        $preUploadAttachments = $entity->getAttachments();
        $postUploadAttachments = $this->upload($preUploadAttachments, $oldAttachmentsValue);

        $entity->setAttachments($postUploadAttachments);

        return true;
    }

    /**
     * process $attachments comming from request.
     *
     * @param array $files
     *
     * @return array
     */
    private function upload(array $files = null, array $oldAttachments = null)
    {
        $attachments = [];

        if ($files === null) {
            return $attachments;
        }

        foreach ($files as $file) {
            $uf = $file['file'];
            $un = $file['name'];

            if ($uf instanceof UploadedFile) {

                do {
                    $uploadedFileName = md5(uniqid()) . ".{$uf->guessClientExtension()}";
                } while (file_exists($this->uploadPath . '/' . $uploadedFileName));

                $uf->move($this->uploadPath, $uploadedFileName);
                $attachments[] = [
                    'name' => $file['name'],
                    'file' => $uploadedFileName,
                ];

            } elseif ($uf === null) {

                foreach ($oldAttachments as $oldEnt) {
                    if ($oldEnt['name'] === $un) {
                        $found = $oldEnt;
                    }
                }

                if (!isset($found)) {
                    throw new \Exception('Are You attempting to add empty attachment?');
                }

                $attachments[] = $found;

            } elseif (is_string($uf)) {

                if (file_exists($this->uploadPath . '/' . $uf)) {
                    $attachments[] = $file;
                }

            } else {
                throw new \Exception('Invalid uploaded field type');
            }
        }

        return $attachments;
    }
}
