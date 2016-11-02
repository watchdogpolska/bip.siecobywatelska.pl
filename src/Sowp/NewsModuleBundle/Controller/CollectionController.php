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
     * @return string
     *
     * @Route("/query", name="sowp_news_collection_query_select2")
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
     * @Route("/dodaj", name="sowp_news_collection_add")
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
                $this->redirectToRoute('sowp_news_collection_show',['id' => $collection->getId()]);
            } else {
                $this->addFlash('error', 'Wystąpił błąd');
            }
        }

        return $this->render('NewsModuleBundle:collection:add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * edit collection entry.
     *
     * @Route("/edytuj/{id}", name="sowp_news_collection_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $req, Collection $collection)
    {
        $form = $this->createForm(addForm::class, $collection);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($collection);
                $em->flush();
                $this->addFlash('notice', 'Zapisano');
            } else {
                $this->addFlash('error', 'Wystąpił błąd');
            }
        }

        return $this->render('NewsModuleBundle:collection:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all Collection entities.
     *
     * @Route("/", name="sowp_news_collection_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $collections = $em->getRepository('NewsModuleBundle:Collection')->findAll();

        return $this->render('NewsModuleBundle:collection:index.html.twig', array(
            'collections' => $collections,
        ));
    }

    /**
     * Finds and displays a Collection entity.
     *
     * @Route("/{id}", name="sowp_news_collection_show")
     * @Method("GET")
     */
    public function showAction(Collection $collection)
    {
        return $this->render('NewsModuleBundle:collection:show.html.twig', array(
            'collection' => $collection,
        ));
    }

    protected function addFlash($type, $content)
    {
        $this->container->get('session')->getFlashBag()->add($type, $content);
    }
}
