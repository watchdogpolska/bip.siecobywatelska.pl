<?php
namespace Sowp\ArticleBundle;


use Gedmo\Uploadable\FilenameGenerator\FilenameGeneratorAlphanumeric;
use Gedmo\Uploadable\FilenameGenerator\FilenameGeneratorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $info = $this->extractFilenameAndExt($file->getClientOriginalName());
        $filename = FilenameGeneratorAlphanumeric::generate($info['filename'], $info['ext']);

        $file->move($this->targetDir, $filename);

        return [
            'filename' => $filename,
            'filesize' => $file->getSize()
        ];
    }

    private function extractFilenameAndExt($filename){
        $hasExtension = strrpos($filename, '.');
        if ($hasExtension) {
            return [
                'filename' => substr($filename, 0, strrpos($filename, '.')),
                'ext' => substr($filename, strrpos($filename, '.'))
            ];
        } else {
            // File without extension
            return [
                'filename' => $filename,
                'ext' => ''
            ];
        }
    }
}