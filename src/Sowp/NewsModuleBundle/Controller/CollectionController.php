<?php

namespace Sowp\NewsModuleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sowp\NewsModuleBundle\Entity\Collection;
use Sowp\NewsModuleBundle\Form\addCollectionForm as addForm;
/**
 * Collection controller.
 *
 * @Route("/kolekcje")
 */
class CollectionController extends Controller
{
    /**
     * add new collection entry
     *
     * @Route("/dodaj", name="addCollection")
     * @Method({"GET","POST"})
     */
    public function addAction(Request $req)
    {
        $collection = new Collection();
        $form = $this->create(addForm::class, $collection);
        $form->handle($req);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()
                     ->getManager()
                     ->persist($collection)
                     ->flush();
                $this->container->get('session')->getFlashBag()->add('notice', 'Zapisano');
            } else {
                $this->container->get('session')->getFlashBag()->add('notice', 'Wystąpił błąd');
            }
        }

        return $this->render('collection/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * add new collection entry
     *
     * @Route("/edytuj/{id}", name="editCollection")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $req, Collection $col)
    {

    }

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
