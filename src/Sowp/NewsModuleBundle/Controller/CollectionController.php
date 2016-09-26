<?php

namespace Sowp\NewsModuleBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\NewsModuleBundle\Entity\Collection;

/**
 * Collection controller.
 *
 * @Route("/kolekcje")
 */
class CollectionController extends Controller
{
    /**
     * Lists all Collection entities.
     *
     * @Route("/", name="kolekcja_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $collections = $em->getRepository('Sowp:NewsModuleBundle:Collection')->findAll();

        return $this->render('collection/index.html.twig', array(
            'collections' => $collections,
        ));
    }

    /**
     * Finds and displays a Collection entity.
     *
     * @Route("/{id}", name="kolekcja_show")
     * @Method("GET")
     */
    public function showAction(Collection $collection)
    {

        return $this->render('collection/show.html.twig', array(
            'collection' => $collection,
        ));
    }
}
