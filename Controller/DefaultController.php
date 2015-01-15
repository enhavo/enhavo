<?php

namespace esperanto\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use esperanto\NewsBundle\Entity\News;
use esperanto\NewsBundle\Form\NewsType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('esperantoNewsBundle:Default:index.html.twig');
    }

    public function dialogAddAction()
    {
        $news = $this->get('esperanto_news.repository.news')->createNew();
        $form = $this->createForm(new NewsType, $news);

        return $this->render('esperantoNewsBundle:Default:dialogAdd.html.twig', array(
            "news" => $news,
            "form" => $form->createView()
        ));
    }

    public function tableAction()
    {
        $news = $this->get('esperanto_news.repository.news')
            ->findAll();
        return $this->render('esperantoRecipeBundle:Default:table.html.twig', array(
            "news" => $news
        ));
    }

    public function saveAction(Request $request)
    {
        $news = $this->get('esperanto_news.repository.news')->createNew();
        $form = $this->createForm(new NewsType, $news);


        $form->handleRequest($request);

        if($form->isValid()) {

            $news->setCreated(time());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($news);
        $em->flush();

        $news = $this->get('esperanto_news.repository.news')->findAll();

        return $this->render('esperantoNewsBundle:Default:index.html.twig', array(
            "recipes" => $news
        ));
    }
}
