<?php

namespace Sowp\NewsModuleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * query collections for Select2Entity.
     *
     * @param Request $request
     *
     * @Route("/query", name="collection_query_select2")
     * @Method("GET")
     */
    public function queryAction(Request $request)
    {
        $collections = [];
        $query = $request->query->get('q');
        $repo = $this->getDoctrine()
                ->getRepository('Sowp\NewsModuleBundle\Entity\Collection');
        $resTmp = $repo->searchTitle($query);

        foreach ($resTmp as $key => $val) {
            $collections[] = [
                'id' => $val->getId(),
                'text' => $val->getTitle(),
            ];
        }

        return new Response(json_encode($collections));
    }

    /**
     * add new collection entry.
     *
     * @Route("/dodaj", name="addCollection")
     * @Method({"GET","POST"})
     */
    public function addAction(Request $req)
    {
        $collection = new Collection();
        $form = $this->createForm(addForm::class, $collection);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($collection);
                $em->flush();
                $this->container->get('session')->getFlashBag()->add('notice', 'Zapisano');
            } else {
                $this->container->get('session')->getFlashBag()->add('error', 'Wystąpił błąd');
            }
        }

        return $this->render('collection/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //below are not done -----------------------------------------------

    /**
     * add new collection entry.
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
