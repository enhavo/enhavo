<?php

namespace esperanto\ProjectBundle\Controller;

use esperanto\NewsletterBundle\Form\Type\SubscriberType;
use esperanto\ProjectBundle\Entity\Page;
use esperanto\ProjectBundle\Entity\News;
use esperanto\ProjectBundle\Entity\Reference;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('esperantoProjectBundle:News:index.html.twig');
    }

    public function pageAction(Page $contentDocument)
    {
        return $this->render('esperantoProjectBundle:Page:page.html.twig', array(
            'page' => $contentDocument
        ));
    }

    public function newsAction(News $contentDocument)
    {
        return $this->render('esperantoProjectBundle:News:news.html.twig', array(
            'news' => $contentDocument
        ));

        return $this->render('esperantoProjectBundle:News:index.html.twig');
    }

    public function referenceAction(Reference $contentDocument)
    {
        return $this->render('esperantoProjectBundle:Reference:index.html.twig');
    }
}

