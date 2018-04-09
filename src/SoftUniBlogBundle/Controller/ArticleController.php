<?php

namespace SoftUniBlogBundle\Controller;

use SoftUniBlogBundle\Entity\Article;
use SoftUniBlogBundle\Entity\User;
use SoftUniBlogBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends Controller
{
    //region CreateFunction
    /**
     * @param Request $request
     * @Route("article/create", name="article_create");
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function create(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('blog_index');
        }
        return $this->render('article/create.html.twig', ['form' => $form->createView()]);
    }
    //endregion

    //region ViewArticle
    /**
     * @Route("article/{id}", name="article_view")
     * @param id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewArticle($id)
    {
        $article = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
        return $this->render("article/view.html.twig", ['article' => $article]);
    }
    //endregion

    /**
     * @Route("article/edit/{id}", name="article_edit")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editArticle(Request $request, $id)
    {
        $article = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->merge($article);
            $em->flush();
            return $this->redirectToRoute('blog_index');
        }
        return $this->render("article/edit.html.twig", ['article' => $article, 'form' => $form->createView()]);
    }
    //region EditArticle
    //endregion

    /**
     * @Route("article/delete/{id}", name="article_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteArticle(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        {
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($article);
                    $em->flush();
                    return $this->redirectToRoute('blog_index');
                }
            }
        }
        return $this->render("article/delete.html.twig", ['article' => $article, 'form' => $form->createView()]);
    }
}
