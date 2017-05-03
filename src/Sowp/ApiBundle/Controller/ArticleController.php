<?php

namespace Sowp\ApiBundle\Controller;

use Sowp\ArticleBundle\Entity\Article;
use Sowp\ArticleBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ArticleController
{
    /**
     * @Route("/{id}", name="api_article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        return new Response(
            $this
                ->get('jms_serializer')
                ->serialize( $article, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/", name="api_article_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {

        $repo = $this->getDoctrine()->getManager()->getRepository(Article::class);
        $pagerAdapter = new DoctrineORMAdapter($repo->getQueryBuilderAll(), false);
        $col = new Pagerfanta($pagerAdapter);
        $result = [];

        $col->setMaxPerPage($request->query->get('per_page', 10));
        $col->setCurrentPage($request->query->get('page', 1));

        foreach ($col->getCurrentPageResults() as $article) {
            $result[] = $article;
        }

        return new Response(
            $this
                ->getSerializer()
                ->serialize($result, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/add", name="api_article_add")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        try {
            $body = $request->getContent();
            $article = $this->getSerializer()->deserialize($body, Article::class, 'json');
            $this->get('doctrine')->getEntityManager()->persist( $article);
            $this->get('doctrine')->getEntityManager()->flush();

            return new Response(
                $this->getSerializer()->serialize( $article, 'json'),
                Response::HTTP_CREATED,
                ['content-type' => 'application/json']
            );
        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @Route("/edit/{id}", name="api_article_edit")
     * @Method({"PUT", "PATCH"})
     */
    public function editAction(Article $article, Request $request)
    {
        $clear = ($request->getMethod() === 'PATCH') ? true : false;

        $form = $this->createForm(ArticleType::class, $article, ['csrf_protection' => false]);
        $form->submit(\json_decode($request->getContent(), true), $clear);

        if (!$form->isValid()) {
            throw new \Exception("", 500);
        }

        try {
            $this->getDoctrine()->getEntityManager()->persist( $article);
            $this->getDoctrine()->getEntityManager()->flush();
        } catch (\Exception $e) {
            throw $e;
        }

        return new Response(
            $this->getSerializer()->serialize( $article, 'json'),
            Response::HTTP_ACCEPTED,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/remove/{id}", name="api_article_delete")
     * @Method("DELETE")
     */
    public function removeAction(Collection $article)
    {
        $this->getDoctrine()->getEntityManager()->remove( $article);
        $this->getDoctrine()->getEntityManager()->flush();
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function getSerializer()
    {
        return $this->get('serializer');
    }

    private function getApiHelper()
    {
        return $this->get('api_helper');
    }

}