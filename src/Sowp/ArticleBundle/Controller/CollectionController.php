<?php

namespace Sowp\ArticleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\ArticleBundle\Entity\Collection;
use Sowp\ArticleBundle\Form\CollectionType;

/**
 * Collection controller.
 *
 * @Route("/admin/collection")
 */
class CollectionController extends Controller
{
    /**
     * Lists all Collection entities.
     *
     * @Route("/", name="admin_collection_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $collections = $em->getRepository('SowpArticleBundle:Collection')->findAll();

        return $this->render('SowpArticleBundle:collection:index.html.twig', array(
            'collections' => $collections,
        ));
    }

    /**
     * Creates a new Collection entity.
     *
     * @Route("/new", name="admin_collection_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $collection = new Collection();
        $form = $this->createForm(CollectionType::class, $collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($collection);
            $em->flush();

            return $this->redirectToRoute('admin_collection_show', array('id' => $collection->getId()));
        }

        return $this->render('SowpArticleBundle:collection:new.html.twig', array(
            'collection' => $collection,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Collection entity.
     *
     * @Route("/{id}", name="admin_collection_show")
     * @Method("GET")
     */
    public function showAction(Collection $collection)
    {
        $deleteForm = $this->createDeleteForm($collection);

        return $this->render('SowpArticleBundle:collection:show.html.twig', array(
            'collection' => $collection,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Collection entity.
     *
     * @Route("/{id}/edit", name="admin_collection_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Collection $collection)
    {
        $deleteForm = $this->createDeleteForm($collection);
        $editForm = $this->createForm('Sowp\ArticleBundle\Form\CollectionType', $collection);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($collection);
            $em->flush();

            return $this->redirectToRoute('admin_collection_show', array('id' => $collection->getId()));
        }

        return $this->render('SowpArticleBundle:collection:edit.html.twig', array(
            'collection' => $collection,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Collection entity.
     *
     * @Route("/{id}", name="admin_collection_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Collection $collection)
    {
        $form = $this->createDeleteForm($collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($collection);
            $em->flush();
        }

        return $this->redirectToRoute('admin_collection_index');
    }

    /**
     * Creates a form to delete a Collection entity.
     *
     * @param Collection $collection The Collection entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Collection $collection)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_collection_delete', array('id' => $collection->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
