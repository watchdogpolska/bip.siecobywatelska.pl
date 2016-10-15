<?php

namespace Sowp\RegistryBundle\Http;

use League\Csv\Writer;
use SplTempFileObject;
use Symfony\Component\HttpFoundation\Response;

class CsvResponse extends Response
{
    protected $data;

    protected $filename = 'export.csv';

    public function __construct($data = array(), $status = 200, $headers = array())
    {
        parent::__construct('', $status, $headers);
        $this->setData($data);
    }

    public function setData(array $data)
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertAll($data);

        $this->data = (string) $csv;
        return $this->update();
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this->update();
    }

    protected function update()
    {
        $this->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $this->filename));
        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'text/csv');
        }
        return $this->setContent($this->data);
    }
}