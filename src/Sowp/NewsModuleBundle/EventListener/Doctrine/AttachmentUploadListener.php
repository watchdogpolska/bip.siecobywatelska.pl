<?php

namespace Sowp\NewsModuleBundle\EventListener\Doctrine;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Sowp\NewsModuleBundle\Entity\News;
use Gedmo\Loggable\Entity\LogEntry;

/**
 * @class AttachmentUploadListener
 * handles News attachments
 */
class AttachmentUploadListener
{
    private $uploadPath;
    private $uploadedFiles = [];

    public function __construct($targetDir)
    {
        $this->uploadPath = $targetDir;
    }

    /**
     * aiming at initial persist
     *
     * @return boolean
     * @param LifecycleEventArgs $arg
     */
    public function prePersist(LifecycleEventArgs $arg)
    {
        $entity = $arg->getEntity();

        switch (true) {
            case ($entity instanceof News):
                $att = $entity->getAttachments();
                $entity->setAttachments($this->uploadArray($att));
                return true;
            case ($entity instanceof LogEntry):
                $logData = $entity->getData();

                if (array_key_exists('attachments', $logData)) {
                    $logDataAttachments = $logData['attachments'];
                    $uploadedAttachments = $this->uploadArray($logDataAttachments, true);
                    $logData['attachments'] = $uploadedAttachments;
                    $entity->setData($logData);
                    return true;
                } else {
                    return false;
                }

            default:
                return false;
        }



    }

    /**
     * triggered at updating ($em->fulsh() on already existing item)
     *
     * @return boolean
     * @param PreUpdateEventArgs $arg
     */
    public function preUpdate(PreUpdateEventArgs $arg)
    {
        $entity = $arg->getEntity(); /** @var $entity Sowp\NewsModuleBundle\Entity\News */

        if (!($entity instanceof News)) {
            return false;
        }

        if ($arg->hasChangedField('attachments')) {
            $att = $this->uploadArray($arg->getNewValue('attachments'));
            $entity->setAttachments($att);
        }

        return true;
    }

    /**
     * process $attachments comming from request
     * @param array $files
     * @param boolean $logEntry set to true in prePersist on moving files
     * @return array
     */
    private function uploadArray(array $files, $logEntry = false)
    {
        $attachmentsArray = [];
        if (!$this->uploadPath ||
            !is_dir($this->uploadPath) ||
            !is_writeable($this->uploadPath)
        ) {
            throw new \Exception("parameter 'sowp_news_module_upload_path' need adjustment");
        }

        foreach ($files as $file) {

            $uplFile = $file['file'];

            switch(true) {
                //its freshly uploaded file
                case ($uplFile instanceof UploadedFile):
                    $uplFilePatnname = $uplFile->getPathname();

                    if (!isset($this->uploadedFiles[$uplFilePatnname])) {
                        $ext = $uplFile->guessClientExtension();
                        do {
                            $uplFileName = md5(uniqid()) . '.' . $ext;
                        } while (file_exists($this->uploadPath . '/' . $uplFileName));
                    }

                    if ($logEntry) {
                        $uplFile->move($this->uploadPath, $uplFileName);
                        $this->uploadedFiles[$uplFilePatnname] = $uplFileName;
                    } else {
                        if (isset($this->uploadedFiles[$uplFilePatnname])) {
                            $uplFileName = $this->uploadedFiles[$uplFilePatnname];
                        } else {
                            $uplFile->move($this->uploadPath, $uplFileName);
                        }
                    }

                    $attachmentsArray[] = [
                        'name' => $file['name'],
                        'file' => $uplFileName
                    ];
                    break;
                //its probably file uploaded before
                default:
                    $attachmentsArray[] = $file;
                    break;
            }

        }

        return $attachmentsArray;
    }
}