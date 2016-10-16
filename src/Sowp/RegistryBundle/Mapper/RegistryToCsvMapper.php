<?php

namespace Sowp\RegistryBundle\Mapper;

use League\Csv\Writer;
use Sowp\RegistryBundle\Entity\Attribute;
use Sowp\RegistryBundle\Entity\Registry;
use Sowp\RegistryBundle\Entity\Row;
use Sowp\RegistryBundle\Entity\Value;
use Sowp\RegistryBundle\Http\FileDownloadResponse;
use SplTempFileObject;

class RegistryToCsvMapper
{
    /** @var  Registry */
    private $registry;

    private $headers;
    private $rows;

    public function __construct(Registry $registry = null)
    {
        $this->setRegistry($registry);
    }

    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
        $this->update();

        return $this;
    }

    private function update()
    {
        if($this->registry == null){
            $this->headers = [];
            $this->rows = [];
            return;
        }

        $this->headers = $this->registry->getAttributes()->map(function(Attribute $attr){
            return $attr->getName();
        })->toArray();

        $this->rows = $this->registry->getRows()->map(function(Row $row){
            return $row->getValues()->map(function(Value $value){
               return (string) $value;
            })->toArray();
        })->toArray();
    }

    public function getCsv()
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne($this->headers);
        $csv->insertAll($this->rows);

        return (string) $csv;
    }

    public function getResponse()
    {
        $data = $this->getCsv();

        return (new FileDownloadResponse($data))
            ->setFilename($this->registry->getSlug() . '.csv')
            ->setMimeType('text/csv');
    }
}