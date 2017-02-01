<?php

namespace Sowp\NewsModuleBundle\SearchProvider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Sowp\NewsModuleBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\SearchModuleBundle\Search\SearchResultInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchProvider implements SearchResultInterface
{
    private $typeName = 'News';

    private $em;
    private $nRepo;
    private $cRepo;
    private $results;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->nRepo = $this->em->getRepository("NewsModuleBundle:News");
        $this->cRepo = $this->em->getRepository("NewsModuleBundle:Collection");
    }

    /**
     * @method array search(string $phrase)
     *
     * function search must return array in format
     *  $returnArray = [
     *      "$typeName" => [
     *          QueryBuilder->getResult(),
     *          QueryBuilder->getResult(),
     *          (...)
     *      ]
     * ];
     *
     * At \Sowp\SearchModuleBundle\Search\SearchManager
     * all these arrays will be merged to provide something like:
     * [
     *  typename => [Result,Result],
     *  typename => [Result],(Positive number of Results)
     *  (...)
     *
     * @param $query string
     * @return array
     */
    public function search($query)
    {
        if (!$query) {
            throw new NotFoundHttpException();
        }

        $rsmNews = new ResultSetMappingBuilder($this->em);
        $rsmNews->addRootEntityFromClassMetadata(News::class, 'n');
        $rsmNews->addScalarResult('score', 'score');
        //$rsmNews->
//        $rsmNews = new ResultSetMapping();
//        $rsmNews->
        $queryNews = $this->em->createNativeQuery(
            "SELECT n.id FROM news n WHERE (MATCH (title,content) AGAINST (':phrase' IN NATURAL LANGUAGE MODE) * 1000000000) > 20000000",
            $rsmNews
        )
        ->setParameter('phrase', $query)
        ->getResult();
        foreach ($queryNews as $n) {
            print $n["score"];
        }
        die();
//        $queryCol = $this->em->createNativeQuery(
//            "SELECT c.id, MATCH (title) AGAINST (':phrase' IN NATURAL LANGUAGE MODE) as score FROM news_collection c",
//            $rsmCol
//        )
//        ->setParameter('phrase', $query)
//        ->getResult();

        ////here
       // $queryCol = $this->em->createNativeQuery()
    }

    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @param string $typeName
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    }
}