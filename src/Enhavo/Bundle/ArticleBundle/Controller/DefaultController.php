<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\ArticleBundle\Form\ArticleType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnhavoArticleBundle:Default:index.html.twig');
    }

    public function dialogAddAction()
    {
        $news = $this->get('enhavo_article.repository.news')->createNew();
        $form = $this->createForm(new ArticleType, $news);

        return $this->render('EnhavoArticleBundle:Default:dialogAdd.html.twig', array(
            "news" => $news,
            "form" => $form->createView()
        ));
    }

    public function tableAction()
    {
        $news = $this->get('enhavo_article.repository.news')
            ->findAll();
        return $this->render('enhavoRecipeBundle:Default:table.html.twig', array(
            "news" => $news
        ));
    }

    public function saveAction(Request $request)
    {
        $news = $this->get('enhavo_article.repository.news')->createNew();
        $form = $this->createForm(new ArticleType, $news);


        $form->handleRequest($request);

        if($form->isValid()) {

            $news->setCreated(time());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($news);
        $em->flush();

        $news = $this->get('enhavo_article.repository.news')->findAll();

        return $this->render('EnhavoArticleBundle:Default:index.html.twig', array(
            "recipes" => $news
        ));
    }
}
