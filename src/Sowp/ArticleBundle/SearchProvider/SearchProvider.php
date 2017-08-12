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

    /** @var EntityManager */
    private $em;

    /** @var EntityRepository */
    private $repo;

    /** @var \Doctrine\ORM\QueryBuilder for results in single mode */
    private $qb;

    private $results = [];

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository('Sowp\ArticleBundle\Entity\Article');
    }

    /**
     * This function must query all important
     * repositories in its module and puts
     * query builders into properties of $this.
     * It calls function that retrieve results or does it itself.
     * Also on each call it clears currently stored results&query builders.
     *
     * @param string $query
     *
     * @return bool
     */
    public function search($query)
    {
        if (!$query) {
            throw new NotFoundHttpException();
        }

        $this->results = [];
        $this->qb = null;

        try {
            $this->qb = $this->repo->createQueryBuilder('article')
                ->andWhere('MATCH_AGAINST (article.title, article.content, :phrase) > 0.01')
                ->setParameter('phrase', $query);

            $this->extractResults();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    private function extractResults()
    {
        $this->results =
            $this->qb
                ->getQuery()
                ->getResult();
    }

    /**
     * Munction getResults*() must be called after $this->search($query)
     * Its role is to take results from search and return them as array
     * eith one value -  array with results under $this->getTypeName().
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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQb(): \Doctrine\ORM\QueryBuilder
    {
        return $this->qb;
    }
}
