<?php

namespace Sowp\RegistryBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sowp\RegistryBundle\Entity\Registry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\RegistryBundle\Entity\Row;

/**
 * Row controller.
 *
 * @Route("/admin/row")
 */
class RowController extends Controller
{
    /**
     * Lists all Row entities.
     *
     * @Route("/{registry_id}/", name="admin_row_index")
     * @ParamConverter("registry", options={"id" = "registry_id"})
     * @Method("GET")
     */
    public function indexAction(Registry $registry)
    {
        $rows = $registry->getRows();

        return $this->render('@SowpRegistry/row/index.html.twig', array(
            'registry' => $registry,
            'rows' => $rows,
        ));
    }

    /**
     * Creates a new Row entity.
     *
     * @Route("/new/{registry_id}", name="admin_row_new")
     * @ParamConverter("registry", options={"id" = "registry_id"})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Registry $registry)
    {
        $row = new Row();
        $row->setRegistry($registry);

        $form = $this->createForm('Sowp\RegistryBundle\Form\RowType', $row);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($row);
            $em->flush();

            return $this->redirectToRoute('admin_row_show', array('id' => $row->getId()));
        }

        return $this->render('@SowpRegistry/row/new.html.twig', array(
            'registry' => $registry,
            'row' => $row,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Row entity.
     *
     * @Route("/{registry_id}/{id}", name="admin_row_show")
     * @ParamConverter("registry", options={"id" = "registry_id"})
     * @Method("GET")
     */
    public function showAction(Registry $registry, Row $row)
    {
        $deleteForm = $this->createDeleteForm($row);

        return $this->render('@SowpRegistry/row/show.html.twig', array(
            'registry' => $registry,
            'row' => $row,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Row entity.
     *
     * @Route("/{registry_id}/{id}/edit", name="admin_row_edit")
     * @ParamConverter("registry", options={"id" = "registry_id"})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Registry $registry, Row $row)
    {
        $deleteForm = $this->createDeleteForm($row);
        $editForm = $this->createForm('Sowp\RegistryBundle\Form\RowType', $row);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($row);
            $em->flush();

            return $this->redirectToRoute('admin_row_show', array(
                'registry_id'=> $registry->getId(),
                'id' => $row->getId()
            ));
        }

        return $this->render('@SowpRegistry/row/edit.html.twig', array(
            'registry' => $registry,
            'row' => $row,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Row entity.
     *
     * @Route("/{id}/{row_id}", name="admin_row_delete")
     * @ParamConverter("row", options={"id" = "row_id"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Registry $registry, Row $row)
    {
        $form = $this->createDeleteForm($row);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($row);
            $em->flush();
        }

        return $this->redirectToRoute('admin_row_index');
    }

    /**
     * Creates a form to delete a Row entity.
     *
     * @param Row $row The Row entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Row $row)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_row_delete', array(
                'id' => $row->getRegistry()->getId(),
                'row_id' => $row->getId()
            )))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
