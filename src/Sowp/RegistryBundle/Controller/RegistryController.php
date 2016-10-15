<?php

namespace Sowp\RegistryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\RegistryBundle\Entity\Registry;
use Sowp\RegistryBundle\Form\RegistryType;

/**
 * Registry controller.
 *
 * @Route("/admin/registry")
 */
class RegistryController extends Controller
{
    /**
     * Lists all Registry entities.
     *
     * @Route("/", name="admin_registry_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $registries = $em->getRepository('SowpRegistryBundle:Registry')->findAll();

        return $this->render('@SowpRegistry/registry/index.html.twig', array(
            'registries' => $registries,
        ));
    }

    /**
     * Creates a new Registry entity.
     *
     * @Route("/new", name="admin_registry_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $registry = new Registry();
        $form = $this->createForm('Sowp\RegistryBundle\Form\RegistryType', $registry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($registry);
            $em->flush();

            return $this->redirectToRoute('admin_registry_show', array('id' => $registry->getId()));
        }

        return $this->render('@SowpRegistry/registry/new.html.twig', array(
            'registry' => $registry,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Registry entity.
     *
     * @Route("/{id}", name="admin_registry_show")
     * @Method("GET")
     */
    public function showAction(Registry $registry)
    {
        $deleteForm = $this->createDeleteForm($registry);

        return $this->render('@SowpRegistry/registry/show.html.twig', array(
            'registry' => $registry,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Registry entity.
     *
     * @Route("/{id}/edit", name="admin_registry_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Registry $registry)
    {
        $deleteForm = $this->createDeleteForm($registry);
        $editForm = $this->createForm('Sowp\RegistryBundle\Form\RegistryType', $registry);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($registry);
            $em->flush();

            return $this->redirectToRoute('admin_registry_show', array('id' => $registry->getId()));
        }

        return $this->render('@SowpRegistry/registry/edit.html.twig', array(
            'registry' => $registry,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Registry entity.
     *
     * @Route("/{id}", name="admin_registry_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Registry $registry)
    {
        $form = $this->createDeleteForm($registry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($registry);
            $em->flush();
        }

        return $this->redirectToRoute('admin_registry_index');
    }

    /**
     * Creates a form to delete a Registry entity.
     *
     * @param Registry $registry The Registry entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Registry $registry)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_registry_delete', array('id' => $registry->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
