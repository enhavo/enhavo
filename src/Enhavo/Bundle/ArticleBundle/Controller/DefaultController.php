<?php

namespace Enhavo\Bundle\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Enhavo\Bundle\NewsBundle\Entity\News;
use Enhavo\Bundle\NewsBundle\Form\NewsType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('enhavoNewsBundle:Default:index.html.twig');
    }

    public function dialogAddAction()
    {
        $news = $this->get('enhavo_news.repository.news')->createNew();
        $form = $this->createForm(new NewsType, $news);

        return $this->render('enhavoNewsBundle:Default:dialogAdd.html.twig', array(
            "news" => $news,
            "form" => $form->createView()
        ));
    }

    public function tableAction()
    {
        $news = $this->get('enhavo_news.repository.news')
            ->findAll();
        return $this->render('enhavoRecipeBundle:Default:table.html.twig', array(
            "news" => $news
        ));
    }

    public function saveAction(Request $request)
    {
        $news = $this->get('enhavo_news.repository.news')->createNew();
        $form = $this->createForm(new NewsType, $news);


        $form->handleRequest($request);

        if($form->isValid()) {

            $news->setCreated(time());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($news);
        $em->flush();

        $news = $this->get('enhavo_news.repository.news')->findAll();

        return $this->render('enhavoNewsBundle:Default:index.html.twig', array(
            "recipes" => $news
        ));
    }
}
