<?php

namespace Sowp\ArticleBundle\SearchProvider;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sowp\SearchModuleBundle\Search\SearchProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchProvider implements SearchProviderInterface
{
    private $typeName = 'Article';

    /** @var EntityManager  */
    private $em;

    /** @var EntityRepository*/
    private $nRepo;

    /** @var EntityRepository*/
    private $cRepo;

    /** @var \Doctrine\ORM\QueryBuilder for results in multimode */
    private $qbMulti;

    /** @var \Doctrine\ORM\QueryBuilder for results in single mode */
    private $qbSingle;

    private $resultsSingle = [];
    private $resultsMulti = [];

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->nRepo = $this->em->getRepository('Sowp\ArticleBundle\Entity\Article');
        $this->cRepo = $this->em->getRepository('Sowp\ArticleBundle\Entity\Collection');
    }

    /**
     * This function must query all important
     * repositories in its module and puts
     * query builders into properties of $this.
     * It calls function that retrieve results or does it itself.
     * Also on each call it clears currently stored results&query builders.
     *
     * @param string $query
     * @return bool
     */
    public function search($query, $numResMulti = 3)
    {
        if (!$query) {
            throw new NotFoundHttpException();
        }

        $this->resultsMulti = [];
        $this->resultsSingle = [];
        $this->qbMulti = null;
        $this->qbSingle = null;

        try {
            $this->qbMulti = $this->nRepo->createQueryBuilder('article')
                ->addSelect("MATCH_AGAINST (article.title, article.content, :phrase) as score")
                ->andWhere('score > 0.01')
                ->setMaxResults($numResMulti)
                ->setParameter('phrase', $query);

            $this->qbSingle = $this->nRepo->createQueryBuilder('article')
                ->addSelect("MATCH_AGAINST (article.title, article.content, :phrase) as score")
                ->andWhere('score > 0.01')
                ->setParameter('phrase', $query);

            $this->extractResults();

        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    private function extractResults()
    {
        $this->resultsMulti =
            $this->qbMulti
                ->getQuery()
                ->getResult();

        $this->resultsSingle =
            $this->qbSingle
                ->getQuery()
                ->getResult();
    }

    /**
     * Munction getResults*() must be called after $this->search($query)
     * Its role is to take results from search and return them as array
     * eith one value -  array with results under $this->getTypeName()
     *
     *  return [
     *      $typeName => [
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
     * @return array
     */
    public function getResultsSingle()
    {
        return [$this->getTypeName() => $this->resultsSingle];
    }

    /**
     * @return array
     */
    public function getResultsMulti()
    {
        return [$this->getTypeName() => $this->resultsMulti];
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQbMulti(): \Doctrine\ORM\QueryBuilder
    {
        return $this->qbMulti;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQbSingle(): \Doctrine\ORM\QueryBuilder
    {
        return $this->qbSingle;
    }

}