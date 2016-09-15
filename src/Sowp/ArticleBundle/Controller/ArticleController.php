<?php

namespace Sowp\ArticleBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\ArticleBundle\Entity\Article;

/**
 * Article controller.
 *
 * @Route("/admin/article")
 */
class ArticleController extends Controller
{
    /**
     * Lists all Article non-deleted entities.
     *
     * @Route(
     *     "/",
     *     name="admin_article_index",
     * )
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SowpArticleBundle:Article');

        $qb = $repo->findPublishedQueryBuilder();

        $articles = new Pagerfanta(new DoctrineORMAdapter($qb));
        $articles->setMaxPerPage(10);
        $articles->setCurrentPage($page);


        return $this->render('SowpArticleBundle:article:index.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * Lists all Article deleted entities.
     *
     * @Route("/deleted", name="admin_article_index_deleted")
     * @Method("GET")
     */
    public function indexDeletedAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SowpArticleBundle:Article');

        $qb = $repo->findDeletedQueryBuilder();

        $articles = new Pagerfanta(new DoctrineORMAdapter($qb));
        $articles->setMaxPerPage(10);
        $articles->setCurrentPage($page);

        return $this->render('SowpArticleBundle:article:index_deleted.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/new", name="admin_article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('Sowp\ArticleBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_article_show', array('id' => $article->getId()));
        }

        return $this->render('SowpArticleBundle:article:new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{id}", name="admin_article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $restoreForm = $this->createRestoreForm($article);

        return $this->render('SowpArticleBundle:article:show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
            'restore_form' => $restoreForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/{id}/edit", name="admin_article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('Sowp\ArticleBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_article_edit', array('id' => $article->getId()));
        }

        return $this->render('SowpArticleBundle:article:edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Article entity.
     *
     * @Route("/{id}", name="admin_article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('admin_article_show', array('id' => $article->getId()));
    }

    /**
     * Deletes a Article entity.
     *
     * @Route("/{id}/restore", name="admin_article_restore")
     * @Method("POST")
     */
    public function restoreAction(Request $request, Article $article)
    {
        $form = $this->createRestoreForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $article->setDeletedAt(null);
            $em->persist($article);

            $em->flush();
        }

        return $this->redirectToRoute('admin_article_show', array('id' => $article->getId()));
    }

    /**
     * Creates a form to delete a Article entity.
     *
     * @param Article $article The Article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to restore a Article entity.
     *
     * @param Article $article The Article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createRestoreForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_article_restore', array('id' => $article->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }
}
