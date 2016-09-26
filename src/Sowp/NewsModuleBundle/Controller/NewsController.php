<?php

namespace Sowp\NewsModuleBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\NewsModuleBundle\Entity\News;

/**
 * News controller.
 *
 * @Route("/artykuly")
 */
class NewsController extends Controller
{
    /**
     * Lists all News entities.
     *
     * @Route("/", name="artykul_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $news = $em->getRepository('Sowp:NewsModuleBundle:News')->findAll();

        return $this->render('news/index.html.twig', array(
            'news' => $news,
        ));
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}", name="artykul_show")
     * @Method("GET")
     */
    public function showAction(News $news)
    {

        return $this->render('news/show.html.twig', array(
            'news' => $news,
        ));
    }
}
