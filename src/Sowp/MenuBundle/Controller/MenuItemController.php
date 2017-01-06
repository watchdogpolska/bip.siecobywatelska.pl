<?php

namespace Sowp\MenuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\MenuBundle\Entity\MenuItem;
use Sowp\MenuBundle\Form\MenuItemType;

/**
 * MenuItem controller.
 *
 * @Route("/admin/menuitem")
 */
class MenuItemController extends Controller
{
    /**
     * Lists all MenuItem entities.
     *
     * @Route("/", name="admin_menuitem_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $menuItems = $em->getRepository('SowpMenuBundle:MenuItem')->getRootNodes();

        return $this->render('SowpMenuBundle:menuitem:index.html.twig', array(
            'menuItems' => $menuItems,
        ));
    }

    /**
     * Creates a new MenuItem entity.
     *
     * @Route("/new", name="admin_menuitem_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $menuItem = new MenuItem();
        $form = $this->createForm('Sowp\MenuBundle\Form\MenuRootType', $menuItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menuItem);
            $em->flush();

            return $this->redirectToRoute('admin_menuitem_show', array('id' => $menuItem->getId()));
        }

        return $this->render('SowpMenuBundle:menuitem:new.html.twig', array(
            'menuItem' => $menuItem,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new MenuItem entity.
     *
     * @Route("/{id}/new", name="admin_menuitem_new_child")
     * @Method({"GET", "POST"})
     */
    public function newChildAction(Request $request, MenuItem $parent)
    {
        $menuItem = new MenuItem();
        $form = $this->createForm('Sowp\MenuBundle\Form\MenuItemType', $menuItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menuItem);
            $menuItem->setParent($parent);
            $em->flush();

            return $this->redirectToRoute('admin_menuitem_show', array('id' => $menuItem->getId()));
        }

        return $this->render('SowpMenuBundle:menuitem:new.html.twig', array(
            'menuItem' => $menuItem,
            'form' => $form->createView(),
        ));
    }

    /**
     * Move a child to up
     *
     * @Route("/{id}/up", name="admin_menuitem_child_up")
     * @Method({"GET", "POST"})
     */
    public function moveUpAction(Request $request, MenuItem $child)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(MenuItem::class);
        $repo->moveUp($child);
        $em->persist($child);
        $em->flush();
        return $this->redirectToRoute('admin_menuitem_index');
    }
    /**
     * Move a child to down
     *
     * @Route("/{id}/down", name="admin_menuitem_child_down")
     * @Method({"GET", "POST"})
     */
    public function moveDownAction(Request $request, MenuItem $child)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(MenuItem::class);
        $repo->moveDown($child);
        $em->persist($child);
        $em->flush();
        return $this->redirectToRoute('admin_menuitem_index');
    }

    /**
     * Finds and displays a MenuItem entity.
     *
     * @Route("/{id}", name="admin_menuitem_show")
     * @Method("GET")
     */
    public function showAction(MenuItem $menuItem)
    {
        $deleteForm = $this->createDeleteForm($menuItem);

        return $this->render('SowpMenuBundle:menuitem:show.html.twig', array(
            'menuItem' => $menuItem,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MenuItem entity.
     *
     * @Route("/{id}/edit", name="admin_menuitem_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MenuItem $menuItem)
    {
        $deleteForm = $this->createDeleteForm($menuItem);
        $editForm = $this->createForm('Sowp\MenuBundle\Form\MenuItemType', $menuItem);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menuItem);
            $em->flush();

            return $this->redirectToRoute('admin_menuitem_edit', array('id' => $menuItem->getId()));
        }

        return $this->render('SowpMenuBundle:menuitem:edit.html.twig', array(
            'menuItem' => $menuItem,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MenuItem entity.
     *
     * @Route("/{id}", name="admin_menuitem_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MenuItem $menuItem)
    {
        $em = $this->getDoctrine()->getManager();
        $rootNodes = $em->getRepository('SowpMenuBundle:MenuItem')->getRootNodes();
        if(count($rootNodes) == 1){
            $this->addFlash('danger', 'You must keep at least one menu.');
            return $this->redirectToRoute('admin_menuitem_index');
        }
        $form = $this->createDeleteForm($menuItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($menuItem);
            $em->flush();
        }

        return $this->redirectToRoute('admin_menuitem_index');
    }

    /**
     * Creates a form to delete a MenuItem entity.
     *
     * @param MenuItem $menuItem The MenuItem entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MenuItem $menuItem)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_menuitem_delete', array('id' => $menuItem->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
