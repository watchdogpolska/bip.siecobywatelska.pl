<?php

namespace Sowp\CollectionBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\CollectionBundle\Entity\Collection;
use Sowp\CollectionBundle\Form\addCollectionForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class CollectionController
 * @package Sowp\CollectionBundle\Controller
 * @Route("/admin/collections")
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
     * @Route("/query", name="admin_collections_query_select2")
     * @Method("GET")
     */
    public function queryAction(Request $request)
    {
        $collections = [];
        $query = $request->query->get('q');
        $resTmp = $this->getDoctrine()
            ->getRepository(Collection::class)
            ->searchTitle($query);

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
     * @Route("/add", name="admin_collections_add")
     * @Method({"GET","POST"})
     */
    public function addAction(Request $req)
    {
        $collection = new Collection();
        $form = $this->createForm(addCollectionForm::class, $collection);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($collection);
                $em->flush();
                $this->addFlash('notice', 'Collection added');

                //return $this->redirectToRoute('sowp_news_collection_show', ['slug' => $collection->getSlug()]);
            }
        }

        return $this->render('CollectionBundle::add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * edit collection entry.
     *
     * @Route("/edit/{slug}", name="admin_collections_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $req, Collection $collection)
    {
        $form = $this->createForm(addCollectionForm::class, $collection);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($collection);
                $em->flush();
                $this->addFlash('notice', 'Operation success');

                return $this->redirectToRoute("admin_collections_show", ['slug' => $collection->getSlug()]);
            }
        }

        return $this->render('CollectionBundle::edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all Collection entities.
     *
     * @Route("/", name="admin_collections_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $collections = $em->getRepository(Collection::class)->findAll();

        return $this->render('CollectionBundle::index.html.twig', array(
            'collections' => $collections,
        ));
    }

    /**
     * Finds and displays a Collection entity.
     *
     * @Route("/{slug}", name="admin_collections_show")
     * @Method("GET")
     */
    public function showAction(Collection $collection)
    {
        return $this->render('CollectionBundle::show.html.twig', array(
            'collection' => $collection,
        ));
    }
}