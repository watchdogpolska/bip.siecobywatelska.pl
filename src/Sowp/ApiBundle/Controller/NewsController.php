<?php
namespace Sowp\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sowp\NewsModuleBundle\Entity\News;
use Sowp\NewsModuleBundle\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package Sowp\ApiBundle\Controller
 * @Route("/messages")
 */
class NewsController extends Controller
{
    /**
     * @Route("/", name="api_news_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {

        $repo = $this->getDoctrine()->getManager()->getRepository(News::class);
        $pagerAdapter = new DoctrineORMAdapter($repo->getQueryBuilderAll(), false);
        $col = new Pagerfanta($pagerAdapter);
        $result = [];

        $col->setMaxPerPage($request->query->get('per_page', 10));
        $col->setCurrentPage($request->query->get('page', 1));

        foreach ($col->getCurrentPageResults() as $news) {
            $result[] = $news;
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
     * @Route("/{id}", name="api_news_show")
     * @Method("GET")
     */
    public function showAction(News $news)
    {
        return new Response(
            $this
                ->get('jms_serializer')
                ->serialize($news, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/add", name="api_news_add")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        try {
            $body = $request->getContent();
            $news = $this->getSerializer()->deserialize($body, News::class, 'json');
            $this->get('doctrine')->getEntityManager()->persist($news);
            $this->get('doctrine')->getEntityManager()->flush();

            return new Response(
                $this->getSerializer()->serialize($news, 'json'),
                Response::HTTP_CREATED,
                ['content-type' => 'application/json']
            );
        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @Route("/edit/{id}", name="api_news_edit")
     * @Method({"PUT", "PATCH"})
     */
    public function editAction(News $news, Request $request)
    {
        $clear = ($request->getMethod() === 'PATCH') ? true : false;

        $form = $this->createForm(NewsType::class, $news, ['csrf_protection' => false]);
        $form->submit(\json_decode($request->getContent(), true), $clear);

        if (!$form->isValid()) {
            throw new \Exception("", 500);
        }

        try {
            $this->getDoctrine()->getEntityManager()->persist($news);
            $this->getDoctrine()->getEntityManager()->flush();
        } catch (\Exception $e) {
            throw $e;
        }

        return new Response(
            $this->getSerializer()->serialize($news, 'json'),
            Response::HTTP_ACCEPTED,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/remove/{id}", name="api_news_delete")
     * @Method("DELETE")
     */
    public function removeAction(News $news)
    {
        $this->getDoctrine()->getEntityManager()->remove($news);
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