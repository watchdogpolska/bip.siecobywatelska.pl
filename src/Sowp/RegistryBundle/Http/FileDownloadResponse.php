<?php

namespace Sowp\RegistryBundle\Http;

use Symfony\Component\HttpFoundation\Response;

class FileDownloadResponse extends Response
{

    protected $filename = 'download.bin';
    protected $mimetype = 'application/octet-strea';

    public function __construct($data, $status = 200, $headers = array())
    {
        parent::__construct($data, $status, $headers);
        $this->update();
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        $this->update();

        return $this;
    }

    public function getMimeType()
    {
        return $this->mimetype;
    }

    public function setMimeType($mimetype)
    {
        $this->mimetype = $mimetype;
        $this->update();

        return $this;
    }

    protected function update()
    {
        $this->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $this->filename));
        $this->headers->set('Content-Type', $this->mimetype);
        return $this;
    }
}